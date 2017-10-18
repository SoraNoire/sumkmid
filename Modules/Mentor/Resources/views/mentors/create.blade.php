@extends('mentor::layouts.master')

@section('title', '| Add Mentor')

@section('content')

<div class='col-sm-12'>

    <h1><i class='fa fa-user-plus'></i> Add User</h1>
    <hr>

    {{ Form::open(array('url' => route('mentorm.index') )) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', '', array('class' => 'form-control', 'style'=>'max-width:300px;')) }}
    </div>

    <div class="form-group">
        {{ Form::label('name', 'Username') }}
        {{ Form::text('username', '', array('class' => 'form-control', 'style'=>'max-width:300px;')) }}
    </div>

    <div class="form-group">
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', '', array('class' => 'form-control', 'style'=>'max-width:300px;')) }}
    </div>

    <div class="form-group">
        {{ Form::label('password', 'Password') }}<br>
        {{ Form::password('password', array('class' => 'form-control', 'style'=>'max-width:300px;')) }}

    </div>

    <div class="form-group">
        {{ Form::label('password', 'Confirm Password') }}<br>
        {{ Form::password('password_confirmation', array('class' => 'form-control', 'style'=>'max-width:300px;')) }}

    </div>

    {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}

</div>

@endsection