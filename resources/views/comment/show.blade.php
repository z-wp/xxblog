@forelse($comments as $comment)
    @if($comment->reply_id)
        {{--ignore--}}
    @else
        @include('comment.comment',['comment'=>$comment])
    @endif
@empty
    <p class="meta-item center-block" style="padding:10px">暂无评论~~</p>
@endforelse
