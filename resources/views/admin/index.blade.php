@extends('admin.layouts.app')
@section('title', 'Overview')
@section('css')
    <style>
        .row a {
            text-decoration: none;
        }

        canvas {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3 col-sm-4 col-6">
            <a href="{{ route('admin.users') }}">
                <div class="info-box">
                    <div class="row">
                        <div class="col-4">
                            <div class="info-icon">
                                <i class="fa fa-user fa-fw"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <span>{{__('web.USER')}}</span>
                            <div class="info-title">{{ $info['user_count'] }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-4 col-6">
            <a href="{{ route('admin.pages') }}">
                <div class="info-box">
                    <div class="row">
                        <div class="col-4">
                            <div class="info-icon">
                                <i class="fa fa-file-text fa-fw"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <span>{{__('web.PAGE')}}</span>
                            <div class="info-title">{{ $info['page_count'] }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-4 col-6">
            <a href="{{ route('admin.posts') }}">
                <div class="info-box">
                    <div class="row">
                        <div class="col-4">
                            <div class="info-icon">
                                <i class="fa fa-book fa-fw"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <span>{{__('web.POST')}}</span>
                            <div class="info-title">{{ $info['post_count'] }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>


        <div class="col-md-3 col-sm-4 col-6">
            <a href="{{ route('admin.comments') }}">
                <div class="info-box">
                    <div class="row">
                        <div class="col-4">
                            <div class="info-icon">
                                <i class="fa fa-comments fa-fw"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <span>{{__('web.COMMENTS')}}</span>
                            <div class="info-title">{{ $info['comment_count'] }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>


        <div class="col-md-3 col-sm-4 col-6">
            <a href="{{ route('admin.tags') }}">
                <div class="info-box">
                    <div class="row">
                        <div class="col-4">
                            <div class="info-icon">
                                <i class="fa fa-tags fa-fw"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <span>{{__('web.TAG')}}</span>
                            <div class="info-title">{{ $info['tag_count'] }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-4 col-6">
            <a href="{{ route('admin.categories') }}">
                <div class="info-box">
                    <div class="row">
                        <div class="col-4">
                            <div class="info-icon">
                                <i class="fa fa-folder fa-fw"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <span>{{__('web.CATEGORY')}}</span>
                            <div class="info-title">{{ $info['category_count'] }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-4 col-6">
            <a href="{{ route('admin.images') }}">
                <div class="info-box">
                    <div class="row">
                        <div class="col-4">
                            <div class="info-icon">
                                <i class="fa fa-image fa-fw"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <span>{{__('web.IMAGE')}}</span>
                            <div class="info-title">{{ $info['image_count'] }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-4 col-6">
            <a href="{{ route('admin.ips') }}">
                <div class="info-box">
                    <div class="row">
                        <div class="col-4">
                            <div class="info-icon">
                                <i class="fa fa-internet-explorer fa-fw"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <span>IP</span>
                            <div class="info-title">{{ $info['ip_count'] }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <canvas data-target="chartjs" chartjs-title="One Year Posts Summary" height="100%"
            chartjs-type="{{ count($labels) < 5 ? 'bar' : 'line' }}"
            chartjs-data='{!! json_encode($data) !!}'
            chartjs-labels='{!! json_encode($labels) !!}'>
    </canvas>
@endsection