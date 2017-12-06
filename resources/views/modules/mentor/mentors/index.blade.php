@extends('mentor::layouts.master')

@section('title', '| Mentors')

@section('content')
<div class="col-sm-12">
    <h1><i class="fa fa-users"></i> Mentors Administration </h1>
    <hr>
    <div class="table-responsive">

        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registered</th>
                    <th>Description</th>
                    <th>Operations</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($mentors as $mentor)
                <tr>

                    <td>{{ $mentor->name }}</td>
                    <td>{{ $mentor->email }}</td>
                    <td>{{ \Carbon\Carbon::parse($mentor->created_at)->format('F d, Y h:ia') }}</td>
                    <td>{{  json_decode($mentor->description)->mentor ?? '' }}</td>

                    <td style="min-width: 110px;">
                    <a href="{{ route('mentorm.edit', $mentor->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                    {!! Form::open(['method' => 'DELETE', 'route' => ['mentorm.destroy', $mentor->id] ]) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}

                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <a href="{{ route('mentor.add') }}" class="btn btn-success">Add Mentor</a>

</div>

@endsection