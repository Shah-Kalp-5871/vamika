<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $query = Offer::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        $offers = $query->latest()->paginate(10);
        $activeOffers = Offer::where('status', 'active')->count();
        $inactiveOffers = Offer::where('status', 'inactive')->count();

        return view('admin.offers.index', compact('offers', 'activeOffers', 'inactiveOffers'));
    }
    
    public function create()
    {
        return view('admin.offers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        Offer::create($validated);

        return redirect()->route('admin.offers.index')->with('success', 'Offer created successfully.');
    }
    
    public function show($id)
    {
        $offer = Offer::findOrFail($id);
        return view('admin.offers.show', compact('offer'));
    }

    public function edit($id)
    {
        $offer = Offer::findOrFail($id);
        return view('admin.offers.edit', compact('offer'));
    }

    public function update(Request $request, $id)
    {
        $offer = Offer::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        $offer->update($validated);

        return redirect()->route('admin.offers.index')->with('success', 'Offer updated successfully.');
    }

    public function destroy($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->delete();

        return redirect()->route('admin.offers.index')->with('success', 'Offer deleted successfully.');
    }
}