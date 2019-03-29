@extends('admin.layouts.app')
@section('title','Comments')
@section('content')
@section('action')
    @if($unverified_count>0)
        <button class="btn btn-sm btn-outline-danger swal-dialog-target"
                data-dialog-msg="{{__('web.REMOVE')}} {{ $unverified_count }} {{__('web.UNVERIFIED_COMMENT')}}"
                data-url="{{ route('comment.delete-un-verified', ['ids'=>$unverified_ids]) }}"
                data-method="delete">{{__('web.DELETE_UNVERIFIED')}}
        </button>
    @endif
@endsection
@if($comments->isEmpty())
    <h3 class="center-block meta-item">No Comments</h3>
@else
    <table class="table table-striped" id="comments-table">
        <thead>
        <tr>
            {{--<th><input type="checkbox" name="select_all"></th>--}}
            <th>{{__('web.USER')}}</th>
            <th>{{__('web.E_MAIL')}}</th>
            <th>{{__('web.ADDRESS')}}</th>
            <th>{{__('web.STATUS')}}</th>
            <th>IP</th>
            <th>{{__('web.OPERATING')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($comments as $comment)
            <?php $commentableData = $comment->getCommentableData();?>
            <tr id="{{ $comment->id }}">
                {{--<td><input type="checkbox"></td>--}}
                <td>
                    @if($comment->user_id)
                        <a href="{{ route('user.show',$comment->username) }}">{{ $comment->username }}</a>
                    @else
                        {{ $comment->username }}
                    @endif
                </td>
                <td><a href="mailto:{{ $comment->email }}">{{ $comment->email }}</a></td>
                <td>
                    @if($commentableData['deleted'])
                        <span data-html="true" data-toggle="tooltip" title="{{ $comment->html_content }}">{{ $commentableData['type'] }}
                            {{__('web.IS_REMOVE')}}</span>
                    @else
                        @if($comment->trashed())
                            <span data-html="true" data-toggle="tooltip"
                                  title="{{ $comment->html_content }}">{{ $commentableData['title'] }}</span>
                        @else
                            <a data-html="true" data-toggle="tooltip" title="{{ $comment->html_content }}"
                               target="_blank"
                               href="{{ $commentableData['url'] }}">{{$commentableData['title'] }}
                            </a>
                        @endif
                    @endif
                </td>
                <td>
                    <span class="p-2 p badge {{ $comment->trashed() ? 'badge-danger':($comment->isVerified() ? 'badge-success' : 'badge-secondary') }}">{{ $comment->trashed() ? __('web.IS_REMOVE'):($comment->isVerified() ? __('web.IS_VERIFIED') : __('web.NOT_VERIFIED')) }}</span>
                </td>
                <td>{{ $comment->ip_id?$comment->ip_id:'NONE' }}</td>
                <td>
                    @if($comment->trashed())
                        <button type="submit"
                                class="btn btn-danger swal-dialog-target"
                                data-dialog-msg="{{__('web.DELETE_THIS_COMMENT_FOREVER')}}？"
                                data-url="{{ route('comment.destroy',[$comment->id,'force'=>'true']) }}"
                                data-method="delete"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="{{__('web.DELETE_FOREVER')}}">
                            <i class="fa fa-trash-o fa-fw"></i>
                        </button>
                        <form class="d-inline-block" method="post" action="{{ route('comment.restore',$comment->id) }}">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-primary" data-toggle="tooltip" data-placement="top"
                                    title="{{__('web.RECOVERY')}}">
                                <i class="fa fa-repeat fa-fw"></i>
                            </button>
                        </form>
                    @else
                        <button type="submit"
                                class="btn btn-danger swal-dialog-target"
                                data-dialog-msg="{{__('web.SURE_DELETE_THIS_COMMENT')}}？"
                                data-toggle="tooltip"
                                data-url="{{ route('comment.destroy',$comment->id) }}"
                                title="{{__('web.DELETE')}}">
                            <i class="fa fa-trash-o fa-fw"></i>
                        </button>
                        <a class="btn btn-info"
                           href="{{ route('comment.edit',[$comment->id,'redirect'=>request()->fullUrl()]) }}">
                            <i class="fa fa-pencil fa-fw"></i>
                        </a>
                    @endif
                    <form class="d-inline-block" method="post"
                          action="{{ route('comment.verify',$comment->id) }}">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-info"
                                data-toggle="tooltip" data-placement="top"
                                title="{{ $comment->isVerified()?'Un Verify':'Verify' }}">
                            <i class="fa fa-{{ $comment->isVerified()?'hand-o-down':'hand-o-up' }} fa-fw"></i>
                        </button>
                    </form>
                    <?php $ip = $comment->ip ? $comment->ip : $comment->ip_id ?>
                    @if($ip == null)
                        <button disabled
                                class="btn btn-default"
                                data-toggle="tooltip"
                                title="NO IP">
                            <i class="fa fa-close fa-fw"></i>
                        </button>
                    @else
                        @include('admin.partials.ip_button',['ip'=>$ip])
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if($comments->lastPage() > 1)
        {{ $comments->links() }}
    @endif
@endif
@endsection
{{--
@section('script')
    <script>
        let dataTable = document.getElementById('comments-table');
        let checkItAll = dataTable.querySelector('input[name="select_all"]');
        let inputs = dataTable.querySelectorAll('tbody>tr>td>input');
        let items = dataTable.querySelectorAll('tbody>tr');
        inputs.forEach(function (input) {
            input.addEventListener('change', function () {
                if (!this.checked) {
                    checkItAll.checked = false;
                } else if (!checkItAll.checked) {
                    let allChecked = true;
                    for (let i = 0; i < inputs.length; i++) {
                        if (!inputs[i].checked) {
                            allChecked = false;
                            break;
                        }
                    }

                    if (allChecked) {
                        checkItAll.checked = true;
                    }
                }
            });
        });

        checkItAll.addEventListener('change', function () {
            inputs.forEach(function (input) {
                input.checked = checkItAll.checked;
            });
        });

        function hh() {
            let ids = [];
            for (let i = 0; i < inputs.length; i++) {
                if (inputs[i].checked) {
                    ids.push(items[i].id)
                }
            }
            console.log(ids)
        }
    </script>
@endsection
--}}
