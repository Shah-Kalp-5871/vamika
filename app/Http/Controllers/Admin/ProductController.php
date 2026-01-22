<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        // Eager load images to avoid N+1
        $products = Product::with(['images' => function($q) {
            $q->where('is_primary', true);
        }])->orderBy('created_at', 'desc')->get();
        
        return view('admin.products.index', compact('products'));
    }
    
    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Allow multiple images
        ]);

        $product = Product::create($validated);

        // Handle Image Uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                $isPrimary = $index === 0; // First uploaded image is primary

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }
    
    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        return view('admin.products.create', compact('product')); // Reusing create view
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $id,
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product->update($validated);

        // Handle New Image Uploads
        if ($request->hasFile('images')) {
            // Check if there are existing images to determine sort order
            $currentMaxSort = $product->images()->max('sort_order') ?? -1;

            foreach ($request->file('images') as $index => $file) {
                 $path = $file->store('products', 'public');
                 
                 // If no images existed, first new one is primary
                 $isPrimary = $product->images()->count() === 0 && $index === 0;

                 ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                    'sort_order' => $currentMaxSort + 1 + $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }
    
    
    public function top(Request $request)
    {
        // Get ranking criteria
        $rankBy = $request->get('rank_by', 'quantity'); // quantity, revenue
        
        // Date range filter
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        
        // Category filter
        $category = $request->get('category');
        
        // Get all products with their sales data
        $productsQuery = Product::with(['images' => function($q) {
            $q->where('is_primary', true);
        }]);
        
        if ($category) {
            $productsQuery->where('category', $category);
        }
        
        $products = $productsQuery->get()->map(function($product) use ($dateFrom, $dateTo) {
            // Calculate quantity sold
            $quantitySold = \App\Models\OrderItem::whereHas('order', function($q) use ($dateFrom, $dateTo) {
                    $q->whereBetween('created_at', [$dateFrom, $dateTo])
                      ->whereIn('status', ['delivered', 'completed']);
                })
                ->where('product_id', $product->id)
                ->sum('quantity');
            
            // Calculate revenue generated
            $revenue = \App\Models\OrderItem::whereHas('order', function($q) use ($dateFrom, $dateTo) {
                    $q->whereBetween('created_at', [$dateFrom, $dateTo])
                      ->whereIn('status', ['delivered', 'completed']);
                })
                ->where('product_id', $product->id)
                ->sum('subtotal');
            
            // Calculate number of orders containing this product
            $orderCount = \App\Models\OrderItem::whereHas('order', function($q) use ($dateFrom, $dateTo) {
                    $q->whereBetween('created_at', [$dateFrom, $dateTo]);
                })
                ->where('product_id', $product->id)
                ->distinct('order_id')
                ->count('order_id');
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'category' => $product->category,
                'price' => $product->price,
                'status' => $product->status,
                'quantity_sold' => $quantitySold,
                'revenue' => $revenue,
                'order_count' => $orderCount,
                'image' => $product->images->first()?->image_path,
            ];
        });
        
        // Filter out products with no sales
        $products = $products->filter(function($product) {
            return $product['quantity_sold'] > 0 || $product['revenue'] > 0;
        });
        
        // Rank products based on criteria
        if ($rankBy === 'quantity') {
            $products = $products->sortByDesc('quantity_sold')->values();
        } elseif ($rankBy === 'revenue') {
            $products = $products->sortByDesc('revenue')->values();
        }
        
        // Take top 20
        $topProducts = $products->take(20);
        
        // Get all categories for filter
        $categories = Product::distinct()->pluck('category')->filter();
        
        // Overall statistics
        $totalProductsSold = $products->sum('quantity_sold');
        $totalRevenue = $products->sum('revenue');
        $avgRevenuePerProduct = $products->count() > 0 ? $totalRevenue / $products->count() : 0;
        
        return view('admin.products.top', compact(
            'topProducts',
            'rankBy',
            'dateFrom',
            'dateTo',
            'category',
            'categories',
            'totalProductsSold',
            'totalRevenue',
            'avgRevenuePerProduct'
        ));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Delete images
        foreach($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        $product->images()->delete();
        
        $product->delete();
        
        return back()->with('success', 'Product deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No products selected.'], 400);
        }

        $products = Product::whereIn('id', $ids)->get();

        foreach ($products as $product) {
            // Delete images
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
            $product->images()->delete();
            $product->delete();
        }

        return response()->json(['success' => true, 'message' => count($ids) . ' product(s) deleted successfully.']);
    }
}