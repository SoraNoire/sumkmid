@extends('auth::layouts.master')

@section('title', '| Edit Profile')

@section('content')

<div class="row">
<div class='col-sm-12 col-md-10 col-md-offset-1'>

    <h1><i class='fa fa-user-plus'></i> Edit {{$mentor->name}}</h1>
    <hr>

    {{ Form::model($mentor, array('route' => array('mentor.update'), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with user data --}}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class' => 'form-control', 'style'=>'max-width:300px;')) }}
    </div>

    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', null, array('class' => 'form-control', 'style'=>'max-width:300px;')) }}
    </div>

    <div class="form-group">
        <?php $desc = json_decode($mentor->description)->mentor ?? '';?>
        {{ Form::label('description', 'Description') }}
        {{ Form::textarea('description', $desc, array('class' => 'form-control', 'style'=>'max-width:300px;')) }}
    </div>

    <div class="form-group">
        {{ Form::label('password', 'Password') }}<br>
        {{ Form::password('password', array('class' => 'form-control', 'style'=>'max-width:300px;')) }}

    </div>

    <div class="form-group">
        {{ Form::label('password', 'Confirm Password') }}<br>
        {{ Form::password('password_confirmation', array('class' => 'form-control', 'style'=>'max-width:300px;')) }}

    </div>

    {{ Form::submit('Save', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>

@endsection