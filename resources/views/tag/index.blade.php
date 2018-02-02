@extends('layouts.app')
@section('title','标签')
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('post.index') }}">博客</a></li>
            <li class="breadcrumb-item active">标签</li>
        </ol>
        <div class="row" data-masonry='{ "itemSelector": ".col", "columnWidth":".col" }'>
            @foreach($tags as $tag)
                <div class="col col-md-3 col-sm-4 col-6 mb-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4 class="card-title">
                                <?php $min = 12; $max = 48;?>
                                <a style="font-size: {{ $min+(int)(($tag->posts_count*1.0/$total)*($max-$min)) }}px" class="text-dark" href="{{ route('tag.show', $tag->name) }}">{{ $tag->name }}</a>
                            </h4>
                            <p class="card-text">
                                <small class="font-italic">{{ $tag->posts_count }} Posts</small>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
