@extends('layouts.app')
@section('title',__('web.ITEM'))
@section('content')
    <div class="container">
        <div id="repo-template" style="display:none">
            <div class="col col-md-4 col-sm-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="[repo.html_url]" target="_blank" class="text-dark" data-pattern-id="[repo.name]">
                                [repo.name]
                            </a>
                        </h4>
                        <p class="card-text">[repo.description]</p>
                        <small class="text-secondary">
                            <span class="mr-3">
                            <i class="fa fa-code"></i>
                            [repo.language]
                            </span>
                            <span class="mr-3">
                            <i class="fa fa-star"></i>
                            [repo.stargazers_count]
                            </span>
                            <span>
                            <i class="fa fa-code-fork"></i>
                            [repo.forks_count]
                            </span>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="projects row justify-content-center">
            <div class="center-block">
                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                <h3>{{__('web.LOADING')}}</h3>
            </div>
        </div>
    </div>
@endsection