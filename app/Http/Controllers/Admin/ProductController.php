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
        
        $categories = Product::CATEGORIES;
        $brands = Product::BRANDS;
        
        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }
    
    public function create()
    {
        $categories = Product::CATEGORIES;
        $brands = Product::BRANDS;
        $subBrands = Product::SUB_BRANDS;
        return view('admin.products.create', compact('categories', 'brands', 'subBrands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku',
            'category' => 'required|string|in:' . implode(',', array_keys(Product::CATEGORIES)),
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'brand' => 'nullable|string|max:100',
            'sub_brand' => 'nullable|string|max:100',
            'unit' => 'nullable|string|max:50',
            'mrp' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Single image up to 5MB
        ]);

        $product = Product::create($validated);

        // Handle Single Image Upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }
    
    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Product::CATEGORIES;
        $brands = Product::BRANDS;
        $subBrands = Product::SUB_BRANDS;
        return view('admin.products.create', compact('product', 'categories', 'brands', 'subBrands')); // Reusing create view
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $id,
            'category' => 'required|string|in:' . implode(',', array_keys(Product::CATEGORIES)),
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'brand' => 'nullable|string|max:100',
            'sub_brand' => 'nullable|string|max:100',
            'unit' => 'nullable|string|max:50',
            'mrp' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'remove_image' => 'nullable|boolean',
        ]);

        $product->update($validated);

        // Handle Image Deletion or Replacement
        if ($request->boolean('remove_image') || $request->hasFile('image')) {
            // Delete old physical images and records
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img->image_path);
            }
            $product->images()->delete();
        }

        // Handle New Image Upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('products', 'public');

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_primary' => true,
                'sort_order' => 0,
            ]);
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