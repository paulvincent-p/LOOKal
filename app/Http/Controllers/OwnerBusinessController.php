<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusinessRequest;
use App\Models\ActivityLog;
use App\Models\Barangay;
use App\Models\Business;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OwnerBusinessController extends Controller
{
    public function index(Request $request)
    {
        $businesses = $request->user()->businesses()->with(['category', 'barangay'])->latest()->get();

        $stats = [];
        $rows = ActivityLog::whereIn('business_id', $businesses->pluck('id'))
            ->selectRaw('business_id, action, COUNT(*) as total')
            ->groupBy('business_id', 'action')
            ->get();
        foreach ($rows as $row) {
            $stats[$row->business_id][$row->action] = $row->total;
        }

        return view('owner.index', compact('businesses', 'stats'));
    }

    public function create()
    {
        return view('owner.form', [
            'business' => new Business(),
            'categories' => Category::orderBy('name')->get(),
            'barangays' => Barangay::orderBy('name')->get(),
            'action' => route('owner.businesses.store'),
            'method' => 'POST',
            'cancel' => route('owner.dashboard'),
            'title' => 'Add your business',
        ]);
    }

    public function store(BusinessRequest $request)
    {
        $data = $request->data();
        $data['owner_id'] = $request->user()->id;
        $data['status'] = 'pending';

        Business::create($data);

        return redirect()->route('owner.dashboard')
            ->with('status', 'Listing submitted. It will appear once an administrator approves it.');
    }

    public function edit(Request $request, Business $business)
    {
        $this->authorizeOwner($request, $business);

        return view('owner.form', [
            'business' => $business,
            'categories' => Category::orderBy('name')->get(),
            'barangays' => Barangay::orderBy('name')->get(),
            'action' => route('owner.businesses.update', $business),
            'method' => 'PUT',
            'cancel' => route('owner.dashboard'),
            'title' => 'Edit your listing',
        ]);
    }

    public function update(BusinessRequest $request, Business $business)
    {
        $this->authorizeOwner($request, $business);

        $data = $request->data($business);
        // Any change goes back to the administrator for a new check.
        $data['status'] = 'pending';
        $data['is_verified'] = false;
        $data['admin_note'] = null;

        $business->update($data);

        return redirect()->route('owner.dashboard')
            ->with('status', 'Listing updated and sent for approval.');
    }

    public function destroy(Request $request, Business $business)
    {
        $this->authorizeOwner($request, $business);

        if ($business->photo_path) {
            Storage::disk('public')->delete($business->photo_path);
        }
        $business->delete();

        return redirect()->route('owner.dashboard')->with('status', 'Listing removed.');
    }

    public function qr(Request $request, Business $business)
    {
        $this->authorizeOwner($request, $business);
        abort_unless($business->status === 'approved', 404);

        return view('owner.qr', [
            'business' => $business,
            'url' => route('listings.show', $business),
        ]);
    }

    private function authorizeOwner(Request $request, Business $business): void
    {
        abort_unless($business->owner_id === $request->user()->id, 403);
    }
}
