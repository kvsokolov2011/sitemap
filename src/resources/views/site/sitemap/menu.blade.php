<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if($menuItems && count($menuItems))
        @foreach ($menuItems as $item)
            @if(isset($item->route))
                <url>
                    <loc>{{route($item->route)}}</loc>
                    <lastmod>{{ $item->updated_at->toAtomString() }}</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>1.0</priority>
                </url>
            @endif
        @endforeach
    @endif
</urlset>
