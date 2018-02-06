<article class="post card {{ $post->cover_img?' with-cover-img':'' }}">
    @if($post->cover_img)
        <img class="post-cover-img" src="{{ $post->cover_img }}">
    @endif
    <div class="post-wrapper">
        <!-- post header -->
        <div class="post-header">
            <h1 class="post-title">
                <a title="{{ $post->title }}" href="{{ route('post.show',$post->slug) }}">
                    {{ $post->title }}
                    <?php $now = \Carbon\Carbon::now();?>
                    <small>
                        @if($post->created_at->diffInDays($now) >= 7 && $post->published_at->diffInDays($now) <= 3)
                            <span class="badge badge-success" data-toggle="tooltip" title="Updated {{ $post->published_at->diffForHumans() }}.">Updated</span>
                        @elseif($post->created_at->diffInDays($now) <= 3)
                            <span class="badge badge-danger" data-toggle="tooltip" title="Created {{ $post->created_at->diffForHumans() }}.">New</span>
                        @endif
                    </small>
                </a>
            </h1>
            <div class="post-meta">
                           <span class="post-time">
                           <i class="fa fa-calendar-o"></i>
                           <time datetime="2016-08-05T00:10:14+08:00">
                           {{ $post->created_at->format('Y-m-d') }}
                           </time>
                           </span>
                <span class="post-category">
                           &nbsp;|&nbsp;
                           <i class="fa fa-folder-o"></i>
                           <a href="{{ route('category.show',$post->category->name) }}">
                           {{ $post->category->name }}
                           </a>
                           </span>
                <span class="post-comments-count">
                           &nbsp;|&nbsp;
                           <i class="fa fa-comments-o fa-fw" aria-hidden="true"></i>
                           <span>{{ $post->comments_count }}</span>
                           </span>
            </div>
        </div>
        {{--post content--}}
        <div class="post-description">
            {!! $post->description !!}
        </div>
        {{--read more--}}
        <div class="post-permalink">
            <a title="阅读全文" href="{{ route('post.show',$post->slug) }}" class="btn btn-outline-dark">阅读全文</a>
        </div>
        {{--post footer--}}
        <div class="post-footer clearfix">
            <div class="pull-left">
                <i class="fa fa-tags"></i>
                @foreach($post->tags as $tag)
                    <a class="mr-2" href="{{ route('tag.show',$tag->name) }}">{{ $tag->name }}</a>
                @endforeach
            </div>
        </div>
    </div>
</article>
