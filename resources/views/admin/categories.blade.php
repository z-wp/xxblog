@extends('admin.layouts.app')
@section('title','Categories')
@section('content')
@section('action')
    <button class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#add-category-modal">New</button>
@endsection
<table class="table table-striped">
    <thead>
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>文章</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($categories as $category)
        <tr>
            <td>{{ $category->name }}</td>
            <td>{{ str_limit($category->description, 64) }}</td>
            <td>{{ $category->posts_count }}</td>
            <td>
                <div>
                    <a href="{{ route('category.edit',$category->id) }}" class="btn btn-info"
                       data-toggle="tooltip" data-placement="top" title="编辑">
                        <i class="fa fa-pencil fa-fw"></i>
                    </a>
                    <button class="btn btn-danger swal-dialog-target"
                            data-toggle="tooltip" data-placement="top" title="删除"
                            data-url="{{ route('category.destroy',$category->id) }}"
                            data-dialog-msg="删除{{ $category->name }}?">
                        <i class="fa fa-trash-o fa-fw"></i>
                    </button>
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
    @include('admin.modals.add-category-modal')
@endsection