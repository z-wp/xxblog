@extends('admin.layouts.app')
@section('title','Application')
@section('content')
    <div>
        <h4>ENV info</h4>
        <table class="table table-striped table-bordered">
            <tbody>
            <tr>
                <th>Laravel version</th>
                <td>{{ app()->version() }}</td>
                <th>Application name</th>
                <td>{{ config('app.name') }}</td>

            </tr>
            <tr>
                <th>Mail username</th>
                <td>{{ config('mail.username') }}</td>
                <th>Mail password</th>
                <td>{{ config('mail.password') ? '******' : '' }}</td>

            </tr>
            <tr>
                <th>Mail host</th>
                <td>{{ config('mail.host') }}</td>
                <th>Mail port</th>
                <td>{{ config('mail.port') }}</td>
            </tr>
            <tr>
                <th>DB connection</th>
                <td>{{ config('database.default') }}</td>
                <th>DB name</th>
                <td>{{ config('database.connections.'.config('database.default').'.database') }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div>
        <h4>Test Application Mail</h4>
        <form method="post" action="{{ route('admin.app.send-mail') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="email">Send to</label>
                <input name="email" type="email" class="form-control" id="email" placeholder="name@example.com">
            </div>
            <div class="form-group">
                <label for="content">Email plain text</label>
                <textarea name="content" class="autosize-target form-control" id="content" rows="3" placeholder="whatever"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send test email</button>
        </form>
    </div>
    <div class="mt-3">
        <h4>
            Failed Jobs
            @if(!$failed_jobs->isEmpty())
                <a class="btn btn-outline-success swal-dialog-target"
                   data-toggle="tooltip"
                   data-placement="right"
                   title="Delete all failed jobs"
                   data-dialog-title="Failed Jobs"
                   data-dialog-msg="Flush {{ count($failed_jobs) }} failed jobs ?"
                   data-url="{{ route('admin.failed-jobs.flush') }}">
                    Flush
                </a>
            @endif
        </h4>
        @if($failed_jobs->isEmpty())
            <div style="text-align: center;"> Congratulations! You don't have failed jobs.</div>
        @else
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Connection</th>
                    <th>Date</th>
                    <th>Exception</th>
                </tr>
                </thead>
                <tbody>
                @foreach($failed_jobs as $failed_job)
                    <tr>
                        <td>{{ $failed_job->id }}</td>
                        <td>{{ $failed_job->connection }}</td>
                        <td>{{ $failed_job->failed_at }}</td>
                        <td title="{{ $failed_job->exception }}">Hover Me</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
