@extends('user.user')
@section('title', 'Notifications')
@section('user-content')
    <div class="p-3">
        @php
            $groupedNotifications = $notifications->groupBy('type');
        @endphp
        <ul class="nav nav-tabs mb-3" id="notificationTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#received-comment" role="tab" aria-controls="home" aria-selected="true">
                    {{__('web.COMMENTS')}}
                    @if(($count = $notifications->where('type',"App\\Notifications\\ReceivedComment")->where('read_at', null)->count()) > 0)
                        <span class="badge badge-danger">{{ $count }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#mentioned-comment" role="tab" aria-controls="profile" aria-selected="false">
                    {{__('web.MENTIONED_COMMENT')}}
                    @if(($count = $notifications->where('type',"App\\Notifications\\MentionedInComment")->where('read_at', null)->count()) > 0)
                        <span class="badge badge-danger">{{ $count }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#base-notification" role="tab" aria-controls="contact" aria-selected="false">
                    {{__('web.BASE_NOTIFICATION')}}
                    @if(($count = $notifications->where('type',"App\\Notifications\\BaseNotification")->where('read_at', null)->count()) > 0)
                        <span class="badge badge-danger">{{ $count }}</span>
                    @endif
                </a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="received-comment" role="tabpanel">
                @if($groupedNotifications->has("App\\Notifications\\ReceivedComment"))
                    @include('user.render-notification', ['notifications'=>$groupedNotifications["App\\Notifications\\ReceivedComment"]])
                @else
                    @include('user.render-notification', ['notifications'=>[]])
                @endif
            </div>
            <div class="tab-pane fade" id="mentioned-comment" role="tabpanel">
                @if($groupedNotifications->has("App\\Notifications\\MentionedInComment"))
                    @include('user.render-notification', ['notifications'=>$groupedNotifications["App\\Notifications\\MentionedInComment"]])
                @else
                    @include('user.render-notification', ['notifications'=>[]])
                @endif
            </div>
            <div class="tab-pane fade" id="base-notification" role="tabpanel">
                @if($groupedNotifications->has("App\\Notifications\\BaseNotification"))
                    @include('user.render-notification', ['notifications'=>$groupedNotifications["App\\Notifications\\BaseNotification"]])
                @else
                    @include('user.render-notification', ['notifications'=>[]])
                @endif
            </div>
        </div>
    </div>
@endsection
