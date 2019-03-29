@extends('layouts.app', ['include_msg'=>false])
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-github fa-fw"></i>{{__('web.SIGN_UP')}}
                    </div>
                    <div class="card-body">
                        <form class="col-md-6" method="POST" action="{{ route('github.store') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="github_id" value="{{ $githubData['github_id'] }}">
                            <div class="form-group">
                                <label for="name" class="form-control-label">{{__('web.NICK_NAME')}}</label>
                                <input id="name" type="text"
                                       class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                       name="name"
                                       value="{{ $githubData['name'] or '' }}">
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-control-label">{{__('web.EMAIL')}}</label>
                                <input id="email" type="email"
                                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                       name="email"
                                       value="{{ $githubData['email'] or '' }}" readonly>
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-control-label">{{__('web.PASSWORD')}}</label>
                                <input id="password" type="password"
                                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password">
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="password-confirm" class="form-control-label">{{__('web.CONFIRM_PASSWORD')}}</label>
                                <input id="password-confirm" type="password"
                                       class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                                       name="password_confirmation">
                                @if ($errors->has('password_confirmation'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-github fa-fw"></i>{{__('web.SIGN_UP')}}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
