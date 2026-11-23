<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barangay;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * One small controller for the two simple name lists: categories and barangays.
 */
class LookupController extends Controller
{
    private function modelFor(string $type): string
    {
        return $type === 'categories' ? Category::class : Barangay::class;
    }

    public function index(string $type)
    {
        $model = $this->modelFor($type);

        return view('admin.lookups', [
            'type' => $type,
            'label' => $type === 'categories' ? 'Categories' : 'Barangays',
            'items' => $model::withCount('businesses')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, string $type)
    {
        $table = $type === 'categories' ? 'categories' : 'barangays';
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60', 'unique:'.$table.',name'],
        ]);

        $this->modelFor($type)::create($data);

        return back()->with('status', 'Added.');
    }

    public function destroy(string $type, int $id)
    {
        $item = $this->modelFor($type)::findOrFail($id);

        if ($item->businesses()->exists()) {
            return back()->withErrors(['name' => 'This item is used by listings, so it cannot be deleted.']);
        }

        $item->delete();

        return back()->with('status', 'Deleted.');
    }
}
