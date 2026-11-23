<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Barangay;
use App\Models\Business;
use App\Models\Category;
use App\Models\EmergencyContact;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function home(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $query = Business::approved()
            ->with(['category', 'barangay'])
            ->withAvg(['reviews as avg_rating' => fn ($r) => $r->where('status', 'visible')], 'rating')
            ->withCount(['reviews as reviews_total' => fn ($r) => $r->where('status', 'visible')]);

        if ($q !== '') {
            $like = '%'.$q.'%';
            $query->where(function ($w) use ($like) {
                $w->where('name', 'like', $like)
                    ->orWhere('description', 'like', $like)
                    ->orWhere('address', 'like', $like)
                    ->orWhereHas('category', fn ($c) => $c->where('name', 'like', $like));
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->integer('category'));
        }

        if ($request->filled('barangay')) {
            $query->where('barangay_id', $request->integer('barangay'));
        }

        $businesses = $query->orderByDesc('is_verified')->orderBy('name')->paginate(12)->withQueryString();

        return view('home', [
            'businesses' => $businesses,
            'categories' => Category::orderBy('name')->get(),
            'barangays' => Barangay::orderBy('name')->get(),
            'q' => $q,
            'townContacts' => EmergencyContact::whereNull('barangay_id')->orderBy('name')->get(),
        ]);
    }

    public function show(Request $request, Business $business)
    {
        $user = $request->user();
        $canPreview = $user && ($user->isAdmin() || $user->id === $business->owner_id);

        if ($business->status !== 'approved' && ! $canPreview) {
            abort(404);
        }

        // Count one view per visitor session.
        $key = 'viewed.'.$business->id;
        if ($business->status === 'approved' && ! $request->session()->has($key)) {
            ActivityLog::create(['business_id' => $business->id, 'action' => 'view']);
            $request->session()->put($key, true);
        }

        $business->load(['category', 'barangay']);
        $reviews = $business->reviews()->where('status', 'visible')->latest()->limit(20)->get();
        $average = $reviews->count() ? round($reviews->avg('rating'), 1) : null;

        return view('listing', compact('business', 'reviews', 'average'));
    }

    public function event(Request $request, Business $business)
    {
        $data = $request->validate([
            'action' => ['required', 'in:call,text,map,messenger,share'],
        ]);

        abort_unless($business->status === 'approved', 404);

        ActivityLog::create(['business_id' => $business->id, 'action' => $data['action']]);

        return response()->noContent();
    }

    public function review(Request $request, Business $business)
    {
        abort_unless($business->status === 'approved', 404);

        $data = $request->validate([
            'reviewer_name' => ['required', 'string', 'max:60'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:255'],
        ]);

        $business->reviews()->create($data);

        return back()->with('status', 'Thank you for your review.');
    }

    public function report(Request $request, Business $business)
    {
        abort_unless($business->status === 'approved', 404);

        $data = $request->validate([
            'reason' => ['required', 'in:Wrong information,Closed or not available,Spam or fake,Abusive content,Other'],
            'details' => ['nullable', 'string', 'max:255'],
        ]);

        $business->reports()->create($data);

        return back()->with('status', 'Thanks. An administrator will check this listing.');
    }

    public function emergency()
    {
        $contacts = EmergencyContact::with('barangay')->orderBy('name')->get();

        return view('emergency', [
            'townContacts' => $contacts->whereNull('barangay_id'),
            'barangayContacts' => $contacts->whereNotNull('barangay_id')->groupBy(fn ($c) => $c->barangay->name),
        ]);
    }
}
