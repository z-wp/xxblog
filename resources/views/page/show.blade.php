@extends('layouts.app')
@section('title',$page->display_name)
@section('keywords',$page->display_name.',')
@section('description',$page->display_name)
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-sm-12 phone-no-padding">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('post.index') }}">博客</a></li>
                    <li class="breadcrumb-item active">{{ $page->display_name }}</li>
                </ol>
                <div class="post-detail shadow">
                    <div class="post-detail-title">
                        {{ $page->display_name }}
                        @can('update',$page)
                            <a class="btn btn-outline-secondary btn-sm" href="{{ route('page.edit',$page->id) }}">编辑</a>
                        @endcan
                    </div>
                    <div class="post-detail-content">
                        {!! $page->html_content !!}
                    </div>
                </div>
                @if($page->isShownComment())
                    @include('widget.comment',[
                        'comment_key'=>'page.'.$page->name,
                        'comment_title'=>$page->display_name,
                        'comment_url'=>route('page.show',$page->name),
                        'commentable'=>$page,
                        'comments'=>isset($comments) ? $comments:[],
                        'redirect'=>request()->fullUrl(),
                        'commentable_type'=>'App\Page'])
                @endif
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('widget.mathjax')
@endsection