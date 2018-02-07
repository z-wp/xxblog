@extends('admin.layouts.app')
@section('title', 'Edit ' . $page->name)
@section('content')
    <div id="upload-img-url" data-upload-img-url="{{ route('upload.image') }}" style="display: none"></div>
    <div id="data" data-id="{{ $page->id . '.by@' . request()->ip() }}">
        <div class="edit-form">
            <form role="form" class="form-horizontal" action="{{ route('page.update',$page->id) }}" method="post">
                @include('admin.page.form-content')
                <input type="hidden" name="_method" value="put">
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            修改
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection