@extends('layouts.app')
@section('description',trim(strip_tags($post->description)))
@section('keywords',$post->tags->implode('name', ',').',')
@section('title',$post->title)
@section('content')
    <div class="container">
        <?php
        $meta = $post->meta;
        $toc = isset($meta['toc']) ? $meta['toc'] : false;
        $toc_enabled = $post->toc_enabled() && $toc;
        ?>
        <div class="row justify-content-center {{ $toc_enabled?'with-toc':'' }}">
            @if($toc_enabled)
                <div class="col-md-3 col-sm-12 mb-3 phone-no-padding">
                    <aside class="sticky-top">
                        <nav class="toc">
                            <div class="card">
                                <h5 class="card-header">
                                    目录
                                </h5>
                                <div class="card-body">
                                    {!! $toc !!}
                                </div>
                            </div>
                        </nav>
                    </aside>
                </div>
            @endif
            <div class="{{ $toc_enabled?'col-md-8':'col-md-10' }} col-sm-12 phone-no-padding">
                <div class="post-detail shadow" id="main-content">
                    @if(!$post->cover_img)
                        <div class="post-detail-title">
                            {{ $post->title }}
                        </div>
                    @endif
                    @can('update',$post)
                        <div class="btn-group btn-group-sm">
                            <a class="btn btn-outline-secondary" href="{{ route('post.edit',$post->id) }}">编辑</a>
                            <a class="btn btn-outline-secondary swal-dialog-target" data-url="{{ route('post.destroy',$post->id) }}" data-dialog-msg="Delete {{ $post->title }} ?">删除</a>
                        </div>
                    @endcan
                    <div class="post-detail-content">
                        {!! $post->html_content !!}
                        <p><a href="#main-content">⬆️</a></p>
                    </div>
                    @include('widget.pay')
                    <div class="post-info-panel">
                        <p class="info">
                            <label class="info-title">版权声明:</label><i class="fa fa-fw fa-creative-commons"></i>自由转载-非商用-非衍生-保持署名（<a
                                    href="https://creativecommons.org/licenses/by-nc-nd/3.0/deed.zh">创意共享3.0许可证</a>）
                        </p>
                        <p class="info">
                            <label class="info-title">创建日期:</label>{{ $post->created_at->format('Y年m月d日') }}
                        </p>
                        @if(isset($post->published_at) && $post->published_at)
                            <p class="info">
                                <label class="info-title">修改日期:</label>{{ $post->published_at->format('Y年m月d日') }}
                            </p>
                        @endif
                        <p class="info">
                            <label class="info-title">文章分类:</label>
                            <a href="{{ route('category.show',$post->category->name) }}">{{ $post->category->name }}</a>
                        </p>
                        <p class="info">
                            <label class="info-title">文章标签:</label>
                            @foreach($post->tags as $tag)
                                <a class="tag" href="{{ route('tag.show',$tag->name) }}">{{ $tag->name }}</a>
                            @endforeach
                        </p>
                        <p class="info">
                            <label class="info-title">评论个数:</label>
                            <span>{{ $post->comments_count }}</span>
                        </p>
                        <p class="info">
                            <label class="info-title">阅读次数:</label>
                            <span>{{ $post->view_count }}</span>
                        </p>
                    </div>
                </div>
                @if(isset($recommendedPosts))
                    @include('widget.recommended_posts',['recommendedPosts'=>$recommendedPosts])
                @endif
                @if(!(isset($preview) && $preview) && $post->isShownComment())
                    @include('widget.comment',[
                            'comment_key'=>$post->slug,
                            'comment_title'=>$post->title,
                            'comment_url'=>route('post.show',$post->slug),
                            'commentable'=>$post,
                            'comments'=>isset($comments) ? $comments:[],
                            'redirect'=>request()->fullUrl(),
                             'commentable_type'=>'App\Post'])
                @endif
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('widget.mathjax')
@endsection