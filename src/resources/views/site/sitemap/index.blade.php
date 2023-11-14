<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if($menu)
        <sitemap>
            <loc>{{ route('sitemap.menu') }}</loc>
            <lastmod>{{ $menu->updated_at->toAtomString() }}</lastmod>
        </sitemap>
    @endif

    @if(config('sitemap-xml.manual') && $manual)
        <sitemap>
            <loc>{{ route('sitemap.manual') }}</loc>
            <lastmod>{{ $manual->toAtomString() }}</lastmod>
        </sitemap>
    @endif

    @if($routes)
        @foreach($routes as $route)
            <sitemap>
                <loc>{{ route('sitemap.route', ['route' => $route->name]) }}</loc>
                <lastmod>{{ $route->updated_at->toAtomString() }}</lastmod>
            </sitemap>
        @endforeach
    @endif
</sitemapindex>
