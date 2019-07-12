@extends('admin.layouts.app')
@section('title','Posts')
@section('content')
    @section('action')
        <a data-toggle="tooltip" data-placement="left" title="Download all posts as markdown file" class="btn btn-sm btn-outline-dark" href="{{ route('post.download-all') }}">Download</a>
    @endsection
    <table class="table table-striped">
        <thead>
        <tr>
            <th>{{__('web.TITLE')}}</th>
            <th>{{__('web.STATUS')}}</th>
            <th>{{__('web.OPERATING')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
            <?php
            $class = 'badge-secondary';
            $status = __('web.UNPUBLISHED');
            if ($post->trashed()) {
                $class = 'badge-danger';
                $status = __('web.IS_REMOVE');
            } else if ($post->isPublished()) {
                $class = 'badge-success';
                $status = __('web.PUBLISHED');
            }
            ?>
            <tr>
                <td title="{{ $post->title }}">{{ str_limit($post->title,64) }}</td>
                <td><span class="p-2 p badge {{ $class }}">{{ $status }}</span></td>
                <td>
                    <div>
                        <a {{ $post->trashed()?'disabled':'' }} href="{{ $post->trashed()?'javascript:void(0)':route('post.edit',$post->id) }}"
                           data-toggle="tooltip" data-placement="top" title="{{__('web.EDIT')}}"
                           class="btn btn-info">
                            <i class="fa fa-pencil fa-fw"></i>
                        </a>
                        @if($post->trashed())
                            <form style="display: inline" method="post" action="{{ route('post.restore',$post->id) }}">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-primary" data-toggle="tooltip"
                                        data-placement="top" title="{{__('web.RECOVERY')}}">
                                    <i class="fa fa-repeat fa-fw"></i>
                                </button>
                            </form>

                        @elseif($post->isPublished())
                            <a href="{{ route('post.show',$post->slug) }}"
                               data-toggle="tooltip" data-placement="top" title="{{__('web.CHECK')}}"
                               class="btn btn-success">
                                <i class="fa fa-eye fa-fw"></i>
                            </a>
                            <form style="display: inline" method="post"
                                  action="{{ route('post.publish',$post->id) }}">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-warning" data-toggle="tooltip"
                                        data-placement="top" title="{{__('web.REVOKE')}}{{__('web.PUBLISH')}}">
                                    <i class="fa fa-undo fa-fw"></i>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('post.preview',$post->slug) }}" data-toggle="tooltip"
                               data-placement="top" title="{{__('web.REVIEW')}}"
                               class="btn btn-success">
                                <i class="fa fa-eye fa-fw"></i>
                            </a>
                            <form style="display: inline" method="post"
                                  action="{{ route('post.publish',$post->id) }}">
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="{{__('web.PUBLISH')}}">
                                    <i class="fa fa-send-o fa-fw"></i>
                                </button>
                            </form>
                        @endif
                        <button class="btn btn-danger swal-dialog-target"
                                data-toggle="tooltip"
                                data-title="{{ $post->title }}"
                                data-dialog-msg="{{__('web.CONFIRM_TO_REMOVE')}}{{__('web.ARTICLE')}}<label>{{ $post->title }}</label>？"
                                title="{{__('web.REMOVE')}}"
                                data-dialog-enable-html="1"
                                data-url="{{ route('post.destroy',$post->id) }}"
                                data-dialog-confirm-text="{{ $post->trashed()?__('web.REMOVE_TIPS'):__('web.REMOVE') }}">
                            <i class="fa fa-trash-o  fa-fw"></i>
                        </button>
                        <a class="btn btn-dark"  data-toggle="tooltip" title="Download as markdown file" href="{{ route('post.download',$post->id) }}">
                            <i class="fa fa-cloud-download fa-fw"></i>
                        </a>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{__('web.COMMENTS')}}
                                <span class="caret"></span>
                            </button>
                            <?php $commentable = $post?>
                            <ul class="dropdown-menu">
                                @if($commentable->allowComment())
                                    <a href="#" data-url="{{ route('post.config',$post->id) }}?allow_resource_comment=false"
                                       data-method="post"
                                       data-dialog-title="{{__('web.DISALLOW_COMMENT')}}"
                                       class="dropdown-item swal-dialog-target">
                                        {{__('web.DISALLOW_COMMENT')}}
                                    </a>
                                @else
                                    <a href="#" data-url="{{ route('post.config',$post->id) }}?allow_resource_comment=true"
                                       data-method="post"
                                       data-dialog-title="{{__('web.ALLOW_COMMENT')}}"
                                       data-dialog-type="success"
                                       class="dropdown-item swal-dialog-target">
                                        {{__('web.ALLOW_COMMENT')}}
                                    </a>
                                @endif
                                @if($commentable->isShownComment())
                                    <a href="#" data-url="{{ route('post.config',$post->id) }}?comment_info=force_disable"
                                       data-method="post"
                                       data-dialog-title="{{__('web.HIDE_COMMENT')}}"
                                       class="dropdown-item swal-dialog-target">
                                        {{__('web.HIDE_COMMENT')}}
                                    </a>
                                @else
                                    <a href="#" data-url="{{ route('post.config',$post->id) }}?comment_info=force_enable"
                                       data-method="post"
                                       data-dialog-title="{{__('web.DISPLAT_COMMENT')}}"
                                       data-dialog-type="success"
                                       class="dropdown-item swal-dialog-target">
                                        {{__('web.DISPLAY_COMMENT')}}
                                    </a>
                                @endif
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $posts->links() }}
@endsection

