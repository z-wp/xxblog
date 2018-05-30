@extends('admin.layouts.app')
@section('title','Images('.$image_count.')')
@section('css')
    <style>
        #images.col, [class*="col-"] {
            padding-right: 0.2rem;
            padding-left: 0.2rem;
        }

        .img-container.card {
            border-radius: 0;
            border: none;
            -webkit-box-shadow: 0 3px 1px -2px rgba(0, 0, 0, .2), 0 2px 2px 0 rgba(0, 0, 0, .14), 0 1px 5px 0 rgba(0, 0, 0, .12);
            box-shadow: 0 3px 1px -2px rgba(0, 0, 0, .2), 0 2px 2px 0 rgba(0, 0, 0, .14), 0 1px 5px 0 rgba(0, 0, 0, .12);
        }

        .img-container img {
            transition: .5s ease;
            backface-visibility: hidden;
        }

        .img-container:hover img {
            -webkit-filter: grayscale(65%);
            filter: grayscale(65%);
        }

        .img-container:hover .img-overlay {
            opacity: 1;
        }

        .img-container .img-overlay {
            transition: .5s ease;
            opacity: 0;
            display: flex;
            position: absolute;
            width: 100%;
            height: 100%;
            overflow-y: auto;
            background-color: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-content: center;
            font-family: "Microsoft YaHei", serif;
        }
    </style>
@endsection
@section('content')
    <div style="max-width: 750px" class="mx-auto UppyDragDrop mb-3" refresh-image-list="1" uppy-height="360"></div>
    <div id="images-list">
        @include('admin.partials.image_list')
    </div>
@endsection
@section('script')
    <script src="https://unpkg.com/imagesloaded@4.1.4/imagesloaded.pkgd.min.js"></script>
    <script>
        new Clipboard('.btn-clipboard');
        $('.btn-clipboard').mouseleave(clearTooltip).tooltip({
            trigger: 'click',
        });

        function clearTooltip(e) {
            $(e.currentTarget).tooltip('hide');
        }

        $('#images').imagesLoaded().progress(function () {
            $('#images').masonry();
        });
    </script>
@endsection