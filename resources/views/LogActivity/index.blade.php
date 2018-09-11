@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Logs del sistema</h1>
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Mensaje</th>
                <th>URL</th>
                <th>Metodo</th>
                <th>Ip</th>
                <th width="300px">Agente del Usuario</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Action</th>
            </tr>
            @if($logs->count())
                @foreach($logs as $key => $log)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $log->subject }}</td>
                        <td class="text-success">{{ $log->url }}</td>
                        <td><label class="label label-info">{{ $log->method }}</label></td>
                        <td class="text-warning">{{ $log->ip_address }}</td>
                        <td class="text-danger">{{ $log->user_agent }}</td>
                        <td>
                            @if (isset($log->user_id))
                                {{ $log->user->name }}
                                @else

                            @endif
                        </td>
                        <td>{{$log->created_at}}</td>
                        <td>
                            @can('delete_log_activities')
                            <button class="btn btn-danger btn-sm">Delete</button>
                            @else
                                -
                            @endcan
                        </td>
                    </tr>
                @endforeach
            @endif
        </table>
    </div>

@endsection
