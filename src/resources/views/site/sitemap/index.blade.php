<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if($menu)
        <sitemap>
            <loc>{{ route('sitemap.menu') }}</loc>
            <lastmod>{{ $menu->updated_at->toAtomString() }}</lastmod>
        </sitemap>
    @endif

    @if($models)
        @foreach($models as $model)
            <sitemap>
                <loc>{{ route('sitemap.model', ['model' => $model->name]) }}</loc>
                <lastmod>{{ $model->updated_at->toAtomString() }}</lastmod>
            </sitemap>
        @endforeach
    @endif
</sitemapindex>
