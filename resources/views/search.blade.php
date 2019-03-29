@extends('layouts.app')
@section('title',__('web.SEARCH'))
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @if($posts->count() == 0)
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{__('web.SEARCH')}} "{{ request('q') }}"</h4>
                            <p class="card-text">{{__('web.SEARCH_NOTHING')}}</p>
                        </div>
                    </div>
                @else
                    <div class="card mb-3">
                        <div class="card-body">
                            <h4 class="card-title">
                                Search for "{{ request('q') }}"
                            </h4>
                        </div>
                    </div>
                    @each('post.item',$posts,'post')
                    @if($posts->lastPage() > 1)
                        {{ $posts->links() }}
                    @endif
                @endif
            </div>
            <div class="col-md-4">
                <div class="slide">
                    @include('layouts.widgets')
                </div>
            </div>
        </div>
    </div>
@endsection
