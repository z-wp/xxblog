@extends('user.user')
@section('title', $user->name)
@section('css')
    <style>
        @media (max-width: 768px) {
            .user-left {
                min-height: 16rem;
            }
        }
    </style>
@endsection
@section('user-content')
    <div class="row">
        <div class="col-md-6 p-3">
            @if($user->avatar)
                <img width="64" height="64" src="{{ $user->avatar  }}" class="rounded-circle mb-3">
            @endif
            <div class="form-group">
                <label>{{__('web.NAME')}}：</label>
                <span>
                    {{ $user->name }}
                    @if(isAdminById($user->id))
                        <span class="role-label">Admin</span>
                    @endif
                </span>
            </div>
            <div class="form-group">
                <label>{{__('web.DESCRIPTION')}}：</label>
                @if($user->description)
                    <span>{{ $user->description }}</span>
                @else
                    <span class="text-secondary font-italic">{{__('web.NULL')}}</span>
                @endif
            </div>
            <div class="form-group">
                <label>{{__('web.WEBSITE')}}：</label>
                @if($user->website)
                    <a href="{{ httpUrl($user->website) }}">{{ httpUrl($user->website) }}</a>
                @else
                    <span class="text-secondary font-italic">{{__('web.NULL')}}</span>
                @endif
            </div>
            @if($user->meta)
                @foreach($user->meta as $key=>$value)
                    <div class="form-group">
                        <label>{{ ucfirst($key) }}：</label>
                        @if($value)
                            <a href="{{ $value }}">{{ $value }}</a>
                        @else
                            <span class="text-secondary font-italic">{{__('web.NULL')}}</span>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
        <?php
        if ($user->profile_image)
            $style = "background: url($user->profile_image) no-repeat center center;background-size: cover";
        else
            $style = '';
        ?>
        <div class="user-left col-md-6 bg-placeholder" style="{{ $style }}">
        </div>
    </div>
@endsection
