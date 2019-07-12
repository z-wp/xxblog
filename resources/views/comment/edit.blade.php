@extends('layouts.app')
@section('title',__('web.BLOG'))
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-pencil fa-fw"></i>{{__('web.EDIT2').__('web.COMMENT')}}
                    </div>
                    <div class="card-body">
                        <form method="post"
                              action="{{ route('comment.update',[$comment->id,'redirect'=>request('redirect')]) }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="put">
                            <div class="form-group">
                                <label for="comment-content">{{__('web.COMMENT_ARTICLE')}}<span class="required">*</span></label>
                                <textarea placeholder="{{__('web.SUPPORT')}}Markdown" style="resize: vertical" id="comment-content"
                                          required
                                          name="content"
                                          rows="5" spellcheck="false"
                                          class="form-control markdown-content autosize-target">{{ $comment->content }}</textarea>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary"
                                       value="{{__('web.EDIT')}}"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
