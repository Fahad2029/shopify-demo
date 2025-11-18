<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    // Get all products from local DB
    public function index()
    {
        return response()->json(Product::all());
    }

    // Get single product by ID
    public function show($id)
    {
        return response()->json(Product::findOrFail($id));
    }

     private function baseUrl(): string
    {
        $domain = rtrim(config('app.shopify_domain', env('SHOPIFY_SHOP_DOMAIN')), '/');
        $version = env('SHOPIFY_ADMIN_API_VERSION', '2025-07');
        return "https://{$domain}/admin/api/{$version}";
    }

    private function client()
    {
        return Http::withHeaders([
            'X-Shopify-Access-Token' => env('SHOPIFY_ACCESS_TOKEN'),
            'Accept'                 => 'application/json',
        ])->retry(3, 500, throw: false); 
    }

    // Fetch products from Shopify and save to local DB
    public function fetchFromShopify()
    {
        $resp = $this->client()->get($this->baseUrl().'/products.json', [
            'limit' => 50, 
        ]);

        if ($resp->failed()) {
            return response()->json(['error' => $resp->json() ?: $resp->body()], $resp->status());
        }
        $products = $resp->json('products');
        // Save or update in local DB
      foreach ($products as $prod) {
            $variant   = $prod['variants'][0] ?? null;
            $image     = $prod['image']['src'] ?? null;

            Product::updateOrCreate(
                ['shopify_id' => $prod['id']], // match existing by Shopify ID
                [
                    'title'             => $prod['title'],
                    'vendor'            => $prod['vendor'],
                    'handle'            => $prod['handle'],
                    'price'             => $variant['price'] ?? null,
                    'inventory_quantity'=> $variant['inventory_quantity'] ?? null,
                    'image'             => $image,
                ]
            );
        }
       return response()->json([
             'message' => 'Products synced from Shopify!',
             'count'   => count($products),
             'products'   => $products,
        ]);
    }

     public function new()
    {
    }
}
