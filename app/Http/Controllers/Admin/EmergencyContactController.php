<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use App\Models\EmergencyContact;
use Illuminate\Http\Request;

class EmergencyContactController extends Controller
{
    public function index()
    {
        return view('admin.emergency', [
            'contacts' => EmergencyContact::with('barangay')->orderBy('name')->get(),
            'barangays' => Barangay::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'number' => ['required', 'regex:/^[0-9+\-\s()]{3,20}$/'],
            'type' => ['nullable', 'string', 'max:40'],
            'barangay_id' => ['nullable', 'exists:barangays,id'],
        ]);

        EmergencyContact::create($data);

        return back()->with('status', 'Contact added.');
    }

    public function destroy(EmergencyContact $contact)
    {
        $contact->delete();

        return back()->with('status', 'Contact deleted.');
    }
}
