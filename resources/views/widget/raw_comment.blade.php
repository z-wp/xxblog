<div class="comments shadow" id="comments">
    <div class="comments-body">
        <div id="comments-container"
             data-api-url="{{ route('comment.show',[$commentable->id,
             'commentable_type'=>$commentable_type,
             'redirect'=>(isset($redirect) && $redirect ? $redirect:'')]) }}">
            @if(isset($comments) && !empty($comments))
                @include('comment.show',$comments)
            @endif
        </div>
        <div id="comment-form-wrapper">
            <form id="comment-form" method="post" action="{{ route('comment.store') }}" class="comment-form">
                {{ csrf_field() }}
                <input type="hidden" name="commentable_id" value="{{ $commentable->id }}">
                <input type="hidden" name="commentable_type" value="{{ $commentable_type }}">
                <?php $final_allow_comment = $commentable->allowComment()?>
                @if(!auth()->check())
                    <div class="form-group">
                        <label class="form-control-label" for="username">{{__('web.USERNAME')}}<span class="text-danger">*</span></label>
                        <input {{ $final_allow_comment?' ':' disabled ' }} class="form-control" id="username" type="text" name="username" placeholder="{{__('web.YOUR_NAME')}}">
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="email">{{__('web.EMAIL')}}<span class="text-danger">*</span></label>
                        <input {{ $final_allow_comment?' ':' disabled ' }} class="form-control" id="email" type="email" name="email" placeholder="{{__('web.YOUR_EMAIL')}}">
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="site">{{__('web.WEBSITE')}}</label>
                        <input {{ $final_allow_comment?' ':' disabled ' }} class="form-control" id="site" type="text" name="site" placeholder="{{__('web.YOUR_WEBSITE')}}">
                    </div>
                @endif
                <div class="form-group">
                    <label for="comment-content">{{__('web.COMMENT_ARTICLE')}},{{__('web.SUPPORT')}} <a href="https://daringfireball.net/projects/markdown/syntax">Markdown</a><span class="text-danger">*</span></label>
                    <textarea {{ $final_allow_comment?' ':' disabled ' }} placeholder="{{__('web.REVIEW_N_SHOW')}}"
                              id="comment-content" name="content"
                              rows="5" spellcheck="false"
                              class="form-control markdown-content autosize-target"></textarea>
                </div>
                @if($final_allow_comment && config('recaptcha.api_site_key'))
                    <input type="hidden" name="recaptcha_api_site_key" value="{{ config('recaptcha.api_site_key') }}">
                    <input type="hidden" name="recaptcha_v3_token">
                    <script src="https://www.recaptcha.net/recaptcha/api.js?render={{ config('recaptcha.api_site_key') }}"></script>
                @endif
                <div class="form-group">
                    <span class="help-block required"><strong id="comment_submit_msg"></strong></span>
                </div>
                <div class="form-group">
                    <input {{ $final_allow_comment?' ':' disabled ' }} type="submit" id="comment-submit" class="btn btn-primary"
                           value="{{__('web.COMMENT_SUBMIT')}}"/>
                </div>
            </form>
        </div>
    </div>
</div>