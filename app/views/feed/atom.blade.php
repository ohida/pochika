<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title>{{ $title }}</title>
    @if ($subtitle)
        <subtitle>{{ $subtitle }}</subtitle>
    @endif
    <link rel="alternate" type="text/html" href="{{ $url }}"/>
    @if (isset($icon_url))
        <icon>{{ $icon_url }}</icon>
    @endif
    @if (isset($logo_url))
        <icon>{{ $logo_url }}</icon>
    @endif
    <updated>{{ $updated }}</updated>
    <author>
        <name>{{ $author }}</name>
    </author>
    <id>{{ $id }}</id>
    <link rel="self" type="application/atom+xml" href="{{ $feed_url }}" />
    <generator>{{ $generator }}</generator>
    @foreach ($entries as $entry)
        <entry>
            <title>{{ $entry['title'] }}</title>
            <link rel="alternate" href="{{ $entry['url'] }}"/>
            <id>{{ $entry['id'] }}</id>
            <updated>{{ $entry['updated'] }}</updated>
            @if (isset($entry['content']))
              <content type="html">{{ $entry['content'] }}</content>
            @endif
        </entry>
    @endforeach
</feed>