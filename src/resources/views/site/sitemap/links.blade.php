<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @if($models && count($models))
        @foreach ($models as $model)
            @if(isset($model->slug))
                <url>
                    <loc>{{route( $route ,[ $name => $model->slug])}}</loc>
                    <lastmod>{{ $model->updated_at->toAtomString() }}</lastmod>
                    <changefreq>daily</changefreq>
                    <priority>0.8</priority>
                </url>
            @endif
        @endforeach
    @endif
</urlset>
