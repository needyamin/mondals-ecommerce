<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\Page;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic sitemap.
     */
    public function index(): Response
    {
        $products = Product::where('status', 'active')->whereNotNull('published_at')->get();
        $categories = Category::where('is_active', true)->get();
        $vendors = Vendor::where('status', 'approved')->get();
        $pages = Page::where('is_active', true)->get();

        $content = view('sitemap.xml', compact('products', 'categories', 'vendors', 'pages'))->render();

        return response($content)->header('Content-Type', 'text/xml');
    }
}
