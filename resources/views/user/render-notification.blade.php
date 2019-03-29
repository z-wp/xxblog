@if(isset($notifications) && $notifications && count($notifications) > 0)
    @foreach($notifications->sortByDesc('created_at') as $notification)
        <?php $notificationData = $notification->data?>
        <div class="media mb-3 {{ ($notification->read_at) ? ' text-muted' : '' }}">
            <div class="media-body">
                <h3 class="mt-0">{{ $notificationData['username'] }}</h3>
                <div class="media-body-content">
                    {!! $notificationData['html_content'] !!}
                    @if(!$notification->read_at)
                        <a class="text-success" href="{{ route('user.read_notification',$notification->id) }}">{{__('web.READ_NOTIFICATION')}}</a>
                    @endif
                    <a class="text-danger" href="{{ route('user.delete_notification',$notification->id) }}">{{__('web.REMOVE')}}</a>
                </div>
            </div>
        </div>
        <hr>
    @endforeach
    @php
        $unreadCount = $notifications->where('read_at', null)->count();
        $readCount = count($notifications) - $unreadCount;
    @endphp
    <div class="btn-group d-flex justify-content-center">
        <a class="btn btn-outline-success mt-3" href="{{ route('user.read_notification', ["all", 'type'=>$notifications[0]->type]) }}">
            <i class="fa fa-eye fw mr-2"></i>{{__('web.READ_ALL_NOTIFICATION')}}({{ $unreadCount }})
        </a>
        <button class="btn btn-outline-danger mt-3 swal-dialog-target"
                data-dialog-msg="Delete {{ $readCount }} read notifications?"
                data-url="{{ route('user.delete_read_notifications', ['type'=>$notifications[0]->type]) }}"
                data-method="delete">
            <i class="fa fa-trash fw mr-2"></i>{{__('web.DELETE_READ')}}<span class="badge badge-danger">{{ $readCount }}</span>
        </button>
    </div>
@else
    <h5 class="text-secondary text-center mt-3">No Notifications</h5>
@endif