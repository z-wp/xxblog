@extends('admin.layouts.app')
@section('title', 'Edit ' . $post->title)
@section('css')
    <link href="//cdn.bootcss.com/select2/4.0.3/css/select2.min.css" rel="stylesheet">
@endsection
@section('content')
    <div id="upload-img-url" data-upload-img-url="{{ route('upload.image') }}" style="display: none"></div>
    <div id="data" data-id="{{ $post->id . '.by@' . request()->ip() }}">
        <img src="{{ isset($post) ? $post->cover_img : old('cover_img') }}" id="cover_img_preview" class="shadow mb-3" style="max-height: 256px;width: 100%;object-fit: cover">
        <div class="card-body edit-form">
            <form role="form" class="form-horizontal" action="{{ route('post.update',$post->id) }}"
                  method="post">
                @include('admin.post.form-content')
                <input type="hidden" name="_method" value="put">
                <button type="submit" class="btn btn-primary">
                    修改
                </button>
            </form>
        </div>
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