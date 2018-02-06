@extends('layouts.app')
@section('title','标签')
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('post.index') }}">博客</a></li>
            <li class="breadcrumb-item active">标签</li>
        </ol>
        <div class="card">
            <div class="card-body">
                @forelse($tags as $tag)
                    <?php $min = 16; $max = 72;?>
                    <a class="text-dark mx-2 my-1" style="font-size: {{ $min+(int)(($tag->posts_count*1.0/$total)*($max-$min+1)) }}px" title="{{ $tag->name }}" href="{{ route('tag.show',$tag->name) }}">
                        {{ $tag->name }}
                    </a>
                @empty <p class="meta-item center-block">No tags.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
