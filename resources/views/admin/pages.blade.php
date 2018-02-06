@extends('admin.layouts.app')
@section('title','Pages')
@section('content')
@section('action')
    <a class="btn btn-sm btn-outline-success" href="{{ route('page.create') }}">New</a>
@endsection
@if($pages->isEmpty())
    <div class="center-block">No pages.</div>
@else
    <table class="table table-striped">
        <thead>
        <tr>
            <th>名称</th>
            <th>url</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach($pages as $page)
            <tr>
                <td>{{ $page->display_name }}</td>
                <td>/{{ $page->name }}</td>
                <td>
                    <div>

                        <a href="{{ route('page.edit',$page->id) }}"
                           data-toggle="tooltip" data-placement="top" title="编辑"
                           class="btn btn-info">
                            <i class="fa fa-pencil fa-fw"></i>
                        </a>
                        <a href="/{{ $page->name }}"
                           data-toggle="tooltip" data-placement="top" title="查看"
                           class="btn btn-success">
                            <i class="fa fa-eye fa-fw"></i>
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
@endsection
