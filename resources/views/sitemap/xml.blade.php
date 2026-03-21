<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    @foreach($pages as $page)
        <url>
            <loc>{{ url('/page/' . $page->slug) }}</loc>
            <changefreq>monthly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach($categories as $category)
        <url>
            <loc>{{ url('/category/' . $category->slug) }}</loc>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
    @foreach($products as $product)
        <url>
            <loc>{{ url('/product/' . $product->slug) }}</loc>
            <changefreq>daily</changefreq>
            <priority>0.9</priority>
        </url>
    @endforeach
    @foreach($vendors as $vendor)
        <url>
            <loc>{{ url('/store/' . $vendor->slug) }}</loc>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach
</urlset>
