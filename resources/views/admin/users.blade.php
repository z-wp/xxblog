@extends('admin.layouts.app')
@section('title','Users')
@section('content')
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>注册日期</th>
            <th>邮箱</th>
            <th>来源</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                @if(isAdminById($user->id))
                    <td>{{ $user->id }}<span class="role-label">Admin</span></td>
                @else
                    <td>{{ $user->id }}</td>
                @endif
                <td>
                    <a href="{{ route('user.show',$user->name) }}">{{ $user->name }}</a>
                    @if($user->github_id)
                        <a href="https://github.com/{{ $user->github_name }}"> [GitHub]</a>
                    @endif
                </td>
                <td>{{ $user->created_at }}</td>
                <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                <td>
                    @if($user->register_from == 'github')
                        <i class="fa fa-github-alt fw" data-toggle="tooltip" title="GitHub"></i>
                    @elseif($user->register_from == 'web_form')
                        <i class="fa fa-globe fw" data-toggle="tooltip" title="Website"></i>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @if($users->lastPage() > 1)
        {{ $users->links() }}
    @endif
@endsection
