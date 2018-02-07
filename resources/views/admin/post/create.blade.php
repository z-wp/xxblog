@extends('admin.layouts.app', ['include_msg'=>false])
@section('title', 'Writing')
@section('css')
    <link href="//cdn.bootcss.com/select2/4.0.3/css/select2.min.css" rel="stylesheet">
@endsection
@section('content')
    <div id="upload-img-url" data-upload-img-url="{{ route('upload.image') }}" style="display: none"></div>
    <div class="edit-form">
        <img src="{{ isset($post) ? $post->cover_img : old('cover_img') }}" id="cover_img_preview" class="shadow mb-3" style="max-height: 256px;width: 100%;object-fit: cover">
        <form class="form-horizontal" action="{{ route('post.store') }}" method="post">
            @include('admin.post.form-content')
            <button type="submit" class="btn btn-primary">
                创建
            </button>
        </form>
    </div>
@endsection

@section('script')
    <script src="//cdn.bootcss.com/select2/4.0.3/js/select2.min.js"></script>
    <script>
        $('#cover_img').change(function () {
            let src = $(this).val();
            $('#cover_img_preview').attr('src', src);
        });
        $("#post-tags").select2({
            tags: true
        });
    </script>
@endsection