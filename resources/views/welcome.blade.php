@extends('layouts.app')

@section('title', 'Discover new packages for Laravel Nova')
@section('meta')
    @og('title', 'Discover new packages for Laravel Nova')
    @og('type', 'website')
    @og('url', url('/'))
    @og('image', url('images/nova-packages-opengraph.png'))
    @og('description', 'Discover new packages for Laravel Nova. Search, browse, or submit your own packages.')
    @og('site_name', config('app.name'))

    <meta name="description" content="Discover new packages for Laravel Nova. Search, browse, or submit your own packages" />
@endsection

@section('content')
    @if (session('status'))
        <div class="bg-green-100 border border-green-300 text-green-600 text-sm px-4 py-3 rounded mb-4">
            {{ session('status') }}
        </div>
    @endif
    <package-index
        :auth="{{ auth()->check() ? 'true' : 'false' }}"
        :type-tags="{{ json_encode($typeTags) }}"
        :popular-tags="{{ json_encode($popularTags) }}"
        :initial-popular-packages="{{ json_encode($popularPackages) }}"
        :initial-recent-packages="{{ json_encode($recentPackages) }}"
        :initial-packages="{{ json_encode($packages) }}">
    </package-index>

    <div v-if="false" class="text-4xl text-center text-grey tracking-wide py-8 my-8">LOADING...</div>
@endsection
