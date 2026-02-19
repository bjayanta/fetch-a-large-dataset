@extends('layouts.app')

@section('content')

<div class="container">
    <h1 class="mb-4">Posts</h1>

    @if(count($posts) > 0)
        @foreach($posts as $post)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $post->title }}</h5>
                    <p>{{ $post->body }}</p>
                </div>
            </div>
        @endforeach
    @else
        <div>
            <p>No posts available.</p>
            <a href="{{ route('import') }}" class="btn btn-primary mb-3">Import Posts from jsonplaceholder.com</a>
        </div>
    @endif

    <div class="d-flex justify-content-center">
        {{ $posts->links() }}
    </div>
</div>

@endsection
