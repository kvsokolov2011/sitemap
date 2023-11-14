<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach (config('sitemap-xml.manual', []) as $item)
        <url>
            <loc>{{route($item)}}</loc>
            <lastmod>{{ $updated_at->toAtomString() }}</lastmod>
            <changefreq>daily</changefreq>
            <priority>1.0</priority>
        </url>
    @endforeach
</urlset>
