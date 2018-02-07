@extends('admin.layouts.app')
@section('title', 'Create Page')
@section('content')
    <div id="upload-img-url" data-upload-img-url="{{ route('upload.image') }}" style="display: none"></div>
    <div class="edit-form">
        <form role="form" class="form-horizontal" action="{{ route('page.store') }}" method="post">
            @include('admin.page.form-content')
            <div class="form-group">
                <div class="col-md-8 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        创建
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection