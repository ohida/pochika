<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($items as $item)
    <url>
        <loc>{{ $item['loc'] }}</loc>
        @if (isset($item['lastmod']))
        <lastmod>{{ $item['lastmod'] }}</lastmod>
        @endif
    </url>
    @endforeach
</urlset>