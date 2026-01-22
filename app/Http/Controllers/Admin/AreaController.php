<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::withCount('shops')->get();
        return view('admin.areas.index', compact('areas'));
    }
    
    public function create()
    {
        return view('admin.areas.create');
    }
    
    public function store(Request $request)
    {
        // Convert comma-separated string to array for validation and storage
        if ($request->has('pincodes_string')) {
             $pincodes = array_map('trim', explode(',', $request->pincodes_string));
             $request->merge(['pincodes' => $pincodes]);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:areas,code',
            'pincodes' => 'nullable|array',
            'pincodes.*' => 'string|digits:6',
            'status' => 'required|in:active,inactive',
        ]);

        Area::create($validated);

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area created successfully.');
    }
    
    public function edit($id)
    {
        $area = Area::findOrFail($id);
        return view('admin.areas.create', compact('area')); // Reusing create view for edit
    }
    
    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);
        
        // Convert comma-separated string to array
        if ($request->has('pincodes_string')) {
             $pincodes = array_map('trim', explode(',', $request->pincodes_string));
             $request->merge(['pincodes' => $pincodes]);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:areas,code,' . $id,
            'pincodes' => 'nullable|array',
            'pincodes.*' => 'string|digits:6',
            'status' => 'required|in:active,inactive',
        ]);

        $area->update($validated);

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area updated successfully.');
    }

    public function destroy($id)
    {
        $area = Area::withCount('shops')->findOrFail($id);
        
        if ($area->shops_count > 0) {
            return back()->with('error', 'Cannot delete area with assigned shops. Please reassign shops first.');
        }

        $area->delete();
        return back()->with('success', 'Area deleted successfully.');
    }
    
    public function performance($id)
    {
        $area = Area::findOrFail($id);
        return view('admin.areas.performance', compact('area'));
    }
    
    public function assignForm()
    {
        return view('admin.areas.assign');
    }
    
    public function viewAssignments()
    {
        // This seems to be a placeholder for a different feature, keeping as is for now or implementing later
        return view('admin.salespersons.view-assignments');
    }
}