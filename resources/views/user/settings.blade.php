@extends('user.user')
@section('title', 'Settings')
@section('user-content')
    @can('manager',$user)
        <div class="p-3">
            <form action="{{ route('user.update.info') }}" method="post">
                {{ csrf_field() }}
                <input name="_method" type="hidden" value="patch">
                <div class="form-group">
                    <label>{{__('web.NAME')}}：</label>
                    <input class="form-control" name="name" type="text" value="{{ $user->name }}">
                </div>
                <div class="form-group">
                    <label>{{__('web.REAL_NAME')}}：</label>
                    <input class="form-control" name="real_name" type="text" value="{{ $user->real_name }}">
                </div>
                <div class="form-group">
                    <label>{{__('web.WEBSITE')}}：</label>
                    <input class="form-control" name="website" type="text" value="{{ $user->website }}">
                </div>
                <div class="form-group">
                    <label>{{__('web.DESCRIPTION')}}：</label>
                    <textarea class="form-control autosize-target" rows="3" name="description">{{ $user->description }}</textarea>
                </div>
                <div class="form-group">
                    <label>Github：</label>
                    <input {{ $user->github_id ? "readonly=''" : '' }} class="form-control" name="github" type="text"
                           value="{{ array_safe_get($user->meta,'github') }}">
                </div>
                <div class="form-group">
                    <label>Facebook：</label>
                    <input class="form-control" name="facebook" type="text"
                           value="{{ array_safe_get($user->meta,'facebook') }}">
                </div>
                <div class="form-group">
                    <label>Weibo：</label>
                    <input class="form-control" name="weibo" type="text" value="{{ array_safe_get($user->meta,'weibo') }}">
                </div>
                <div class="form-group">
                    <label>Twitter：</label>
                    <input class="form-control" name="twitter" type="text" value="{{ array_safe_get($user->meta,'twitter') }}">
                </div>
                <input type="submit" class="btn btn-primary" value="修改">
            </form>
        </div>
    @endcan
@endsection
