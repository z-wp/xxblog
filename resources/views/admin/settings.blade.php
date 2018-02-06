@extends('admin.layouts.app')
@section('title','Settings')
@section('content')
    <form id="setting-form" action="{{ route('admin.save-settings') }}" method="post">
        <div class="pl-3 pr-3">
            <div class="d-block">
                <div class="settings">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-normal-tab" data-toggle="tab" href="#nav-normal" role="tab" aria-controls="nav-normal" aria-selected="true">常用</a>
                            @foreach($groups as $group)
                                <a class="nav-item nav-link" id="nav-group{{ $loop->index }}-tab" data-toggle="tab" href="#nav-group{{ $loop->index }}" role="tab"
                                   aria-controls="nav-group{{ $loop->index }}" aria-selected="false">{{ $group['group_name'] }}</a>
                            @endforeach
                            <a class="nav-item nav-link" id="nav-other-tab" data-toggle="tab" href="#nav-other" role="tab" aria-controls="nav-other" aria-selected="false">其它</a>
                        </div>
                    </nav>
                    <div class="tab-content mt-3" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-normal" role="tabpanel" aria-labelledby="nav-normal-tab">
                            @foreach($radios as $variable)
                                <?php
                                $variable_name = $variable['name'];
                                $type = isset($variable['type']) ? $variable['type'] : 'text';// default text
                                $default = isset($variable['default']) ? $variable['default'] : '';
                                $final_value = isset($$variable_name) ? $$variable_name : $default;
                                ?>
                                <div class="form-group">
                                    @foreach($variable['values'] as $key => $value)
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input"
                                                       {{ $final_value == $key ? ' checked ':'' }}
                                                       name="{{ $variable_name }}"
                                                       value="{{ $key }}">{{ $value }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                        @foreach($groups as $group)
                            <div class="tab-pane fade" id="nav-group{{ $loop->index }}" role="tabpanel" aria-labelledby="nav-group{{ $loop->index }}-tab">
                                @foreach($group['children'] as $variable)
                                    @include('admin.partials.config_variable', ['variable'=>$variable])
                                @endforeach
                            </div>
                        @endforeach
                        <div class="tab-pane fade" id="nav-other" role="tabpanel" aria-labelledby="nav-other-tab">
                            @foreach($others as $variable)
                                @include('admin.partials.config_variable', ['variable'=>$variable])
                            @endforeach
                        </div>
                    </div>
                </div>

                {{ csrf_field() }}
                <div class="col-md-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-outline-success">
                        保存
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

