@extends('layouts.master')

@section('PlaceHolderTitle', 'Sign up')

@section('PlaceHolderMainForm')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1 class="panel-title">Sign up</h1>
                </div>
                <div class="panel-body">
                  {{ Form::open(array('url' => '/signup')) }}
                    <div class="form-group email">
                        {{ Form::label('email', 'Email:') }}
                        {{ Form::text('email', null, array('class' => 'form-control')) }}
                        <p class="text-danger">{{ $errors->first('email') }}</p>
                    </div>
                    <div class="form-group password">
                        {{ Form::label('password', 'Password:') }}
                        {{ Form::password('password', array('class' => 'form-control')) }}
                        <p class="text-danger">{{ $errors->first('password') }}</p>
                    </div>
                    <div class="form-group password">
                        {{ Form::label('password_confirmation', 'Re-enter Password:') }}
                        {{ Form::password('password_confirmation', array('class' => 'form-control')) }}
                        <p class="text-danger">{{ $errors->first('password_confirmation') }}</p>
                    </div>
                    <div class="form-group">
                        {{ Form::submit('Sign up', array('class' => 'btn btn-primary btn-md')) }}
                        <a href="/login" class="btn btn-link btn-md">Log in</a>
                    </div>
                  {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop