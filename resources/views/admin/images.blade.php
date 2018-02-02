@extends('admin.layouts.app')
@section('title','Images')
@section('css')
    <style>
        #images.col, [class*="col-"] {
            padding-right: 0.1rem;
            padding-left: 0.1rem;
        }

        .img-container.card {
            border-radius: 0;
        }

        .img-container img {
            transition: .5s ease;
            backface-visibility: hidden;
        }

        .img-container:hover img {
            -webkit-filter: blur(.1) grayscale(35%);
            filter: blur(.1) grayscale(35%);
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
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-content: center;
            font-family: "Microsoft YaHei", serif;
        }
    </style>
@endsection
@section('content')
    <div class="d-flex justify-content-center">
        <div class="UppyDragDrop mb-3"></div>
    </div>
    <div id="images-list">
        @include('admin.partials.image_list')
    </div>
@endsection
@section('script')
    <script src="//cdn.bootcss.com/clipboard.js/1.5.12/clipboard.min.js"></script>
    <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
    <script>
        new Clipboard('.btn-clipboard');
        $('.btn-clipboard').tooltip({
            trigger: 'click',
        });
        $('#images').imagesLoaded().progress(function () {
            $('#images').masonry();
        });
        $('body').on('click', function (e) {
            $('[data-toggle=tooltip]').each(function () {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0) {
                    $(this).tooltip('hide');
                }
            });
        });
    </script>
@endsection