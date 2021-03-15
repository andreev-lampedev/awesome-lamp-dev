@extends('layouts.app')

@section('content')
    <package-search-results
        :auth="{{ auth()->check() ? 'true' : 'false' }}"
        :query="'{{ $query }}'"
        :initial-packages="{{ json_encode($packages) }}">
    </package-search-results>

    <div v-if="false" class="text-4xl text-center text-grey tracking-wide py-8 my-8">LOADING...</div>
@endsection
