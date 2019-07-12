@extends('layouts.app')
@section('title',__('web.CLASSIFICATION'))
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('post.index') }}">{{__('web.BLOG')}}</a></li>
            <li class="breadcrumb-item active">{{__('web.CLASSIFICATION')}}</li>
        </ol>
        <div id="categories" class="row" data-masonry='{ "itemSelector": ".col", "columnWidth":".col" }'>
            @foreach($categories as $category)
                <div class="col col-md-4 col-sm-6 mb-3">
                    <div class="card text-center">
                        @if($category->cover_img)
                            <img class="card-img-top" src="{{ $category->cover_img }}">
                        @endif
                        <div class="card-body">
                            <h3 class="card-title">
                                <a class="text-dark" href="{{ route('category.show', $category->name) }}">{{ $category->name }}</a>
                            </h3>
                            @if($category->description)
                                <p class="card-text">{{ $category->description }}</p>
                            @endif
                            <p class="card-text">
                                <small class="font-italic">{{ $category->posts_count }} Posts</small>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@section('script')
    <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
    <script>
        $('#categories').imagesLoaded().progress(function () {
            $('#categories').masonry();
        });
    </script>
@endsection