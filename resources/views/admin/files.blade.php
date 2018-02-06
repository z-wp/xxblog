@extends('admin.layouts.app')
@section('title','Files')
@section('content')
    <div class="card mb-3">
        <div class="card-header">
            <h6><i class="fa fa-file-archive-o fa-fw"></i>文件</h6>
        </div>
        <div class="card-body">
            <form class="form-inline justify-content-center" action="{{ route('upload.file') }}"
                  datatype="image"
                  enctype="multipart/form-data" method="post">
                {{ csrf_field() }}
                <div class="form-group">
                    <input id="image" class="form-control-file" type="file" name="file">
                </div>
                <button type="submit" class="btn btn-outline-success ml-3">
                    Upload
                </button>
            </form>
        </div>
    </div>

    <div class="mt-3">
        <table class="table table-striped">
            <tbody>
            @forelse($files as $file)
                <tr>
                    <td>{{ $file->type }}</td>
                    <td>{{ $file->name }}</td>
                    <td>
                        <button id="clipboard-btn" class="btn btn-default"
                                type="button"
                                data-clipboard-text="{{ $file->url }}"
                                data-toggle="tooltip"
                                data-placement="left"
                                title="Copied">
                            <i class="fa fa-copy fa-fw"></i>
                        </button>
                        <a class="btn btn-info"
                           href="{{ $file->url }}"
                           data-method="delete">
                            <i class="fa fa-cloud-download fa-fw"></i>
                        </a>
                        <button class="btn btn-danger swal-dialog-target"
                                data-dialog-msg="确定删除{{ $file->key }}？"
                                data-url="{{ route('delete.file').'?key='.$file->key."&type=".$file->type }}">
                            <i class="fa fa-trash-o fa-fw"></i>
                        </button>
                    </td>
                </tr>
            @empty
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
@section('script')
    <script src="//cdn.bootcss.com/clipboard.js/1.5.12/clipboard.min.js"></script>
    <script>
        new Clipboard('.btn');
        $('.btn').tooltip({
            trigger: 'click',
        });
    </script>
@endsection
