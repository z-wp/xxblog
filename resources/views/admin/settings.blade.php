@extends('admin.layouts.app')
@section('title','Settings')
@section('content')
    <form id="setting-form" action="{{ route('admin.save-settings') }}" method="post">
        <div class="pl-3 pr-3">
            <div class="d-block">
                <div class="settings">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            @foreach($groups as $group)
                                <a class="nav-item nav-link {{ $loop->index==0?'active':'' }}" id="nav-group{{ $loop->index }}-tab" data-toggle="tab" href="#nav-group{{ $loop->index }}" role="tab"
                                   aria-controls="nav-group{{ $loop->index }}" aria-selected="false">{{ $group['group_name'] }}</a>
                            @endforeach
                        </div>
                    </nav>
                    <div class="tab-content mt-3" id="nav-tabContent">
                        @foreach($groups as $group)
                            <div class="tab-pane fade {{ $loop->index==0?'show active':'' }}" id="nav-group{{ $loop->index }}" role="tabpanel" aria-labelledby="nav-group{{ $loop->index }}-tab">
                                @foreach($group['children'] as $variable)
                                    @include('admin.partials.config_variable', ['variable'=>$variable])
                                @endforeach
                            </div>
                        @endforeach
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
@section('script')
    <script>
        // textarea in tab-content is invisible, autosize will not effect. Update after visible.
        $("[data-toggle=tab]").click(function () {
            setTimeout(function () {
                autosize.update($('.autosize-target'), $(this).attr('href'));
            }, 200);
        });
    </script>
@endsection

