@extends('admin.layouts.app')
@section('title','Categories')
@section('content')
@section('action')
    <button class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#add-category-modal">New</button>
@endsection
<table class="table table-striped">
    <thead>
    <tr>
        <th>{{__('web.NAME')}}</th>
        <th>{{__('web.DESCRIPTION')}}</th>
        <th>{{__('web.ARTICLE')}}</th>
        <th>{{__('web.OPERATING')}}</th>
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
                       data-toggle="tooltip" data-placement="top" title="{{__('web.EDIT')}}">
                        <i class="fa fa-pencil fa-fw"></i>
                    </a>
                    <button class="btn btn-danger swal-dialog-target"
                            data-toggle="tooltip" data-placement="top" title="{{__('web.REMOVE')}}"
                            data-url="{{ route('category.destroy',$category->id) }}"
                            data-dialog-msg="{{__('web.REMOVE')}}{{ $category->name }}?">
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