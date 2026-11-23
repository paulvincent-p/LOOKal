<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessRequest;
use App\Models\Barangay;
use App\Models\Business;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $query = Business::with(['category', 'barangay', 'owner'])->latest();
        if (in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $query->where('status', $status);
        }

        $businesses = $query->paginate(15)->withQueryString();

        return view('admin.businesses', compact('businesses', 'status'));
    }

    public function export()
    {
        $rows = Business::with(['category', 'barangay', 'owner'])->orderBy('name')->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Name', 'Category', 'Barangay', 'Phone', 'Owner', 'Status', 'Verified', 'Created']);
            foreach ($rows as $b) {
                fputcsv($out, [
                    $b->id, $b->name, $b->category->name, $b->barangay->name, $b->phone,
                    $b->owner->name, $b->status, $b->is_verified ? 'yes' : 'no', $b->created_at,
                ]);
            }
            fclose($out);
        }, 'lookal-businesses.csv', ['Content-Type' => 'text/csv']);
    }

    public function edit(Business $business)
    {
        return view('owner.form', [
            'business' => $business,
            'categories' => Category::orderBy('name')->get(),
            'barangays' => Barangay::orderBy('name')->get(),
            'action' => route('admin.businesses.update', $business),
            'method' => 'PUT',
            'cancel' => route('admin.businesses.index', ['status' => 'all']),
            'title' => 'Edit listing (admin)',
        ]);
    }

    public function update(BusinessRequest $request, Business $business)
    {
        $business->update($request->data($business));

        return redirect()->route('admin.businesses.index', ['status' => 'all'])->with('status', 'Listing updated.');
    }

    public function updateStatus(Request $request, Business $business)
    {
        $data = $request->validate([
            'status' => ['required', 'in:approved,rejected,pending'],
            'admin_note' => ['nullable', 'string', 'max:255'],
        ]);

        $business->update([
            'status' => $data['status'],
            'admin_note' => $data['status'] === 'rejected' ? ($data['admin_note'] ?? null) : null,
            'is_verified' => $data['status'] === 'approved' && $request->boolean('is_verified'),
        ]);

        return back()->with('status', 'Listing marked as '.$data['status'].'.');
    }

    public function destroy(Business $business)
    {
        if ($business->photo_path) {
            Storage::disk('public')->delete($business->photo_path);
        }
        $business->delete();

        return back()->with('status', 'Listing deleted.');
    }
}
