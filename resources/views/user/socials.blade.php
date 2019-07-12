@extends('user.user')
@section('title', 'Social')
@section('user-content')
    @can('manager',$user)
        <div class="p-3">
            <div class="form-group">
                <label>GitHub：</label>
                @if(!$user->github_id)
                    <a class="btn btn-outline-primary" href="{{ route('github.login') }}">
                        {{__('web.BIND')}}<i class="fa fa-github fa-lg fa-fw"></i>
                    </a>
                @else
                    <a class="btn btn-outline-success" href="https://github.com/{{ $user->github_name }}">
                        {{__('web.IS_BIND')}}<i class="fa fa-github fa-lg fa-fw"></i>
                    </a>
                @endif
            </div>
        </div>
    @endcan
@endsection
