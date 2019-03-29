@extends('admin.layouts.app')
@section('title', 'Edit '.$tag->name)
@section('content')
    <div class="row justify-content-start">
        <div class="col-md-6">
            <form action="{{ route('tag.update',$tag->id) }}"
                  method="post">
                <div class="form-group">
                    <label for="name" class="control-label">{{__('web.TAG_NAME')}}</label>
                    <input id="name" type="text"
                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                           value="{{ $tag->name }}"
                           autofocus>

                    @if ($errors->has('name'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('name') }}</strong>
                        </div>
                    @endif
                </div>
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        {{__('web.EDIT')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection