@extends('layouts.master')

@section('PlaceHolderTitle', 'Verify Email Address')

@section('PlaceHolderMainForm')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1 class="panel-title">Verify Email Address</h1>
                </div>
                <div class="panel-body">
                    <p>We sent an email with a verification code to <strong>{{{ Auth::user()->email }}}</strong> during the registration process.</p>
                    <p>If you need another code, click the button below.</p>
                    {{ Form::open(array('url' => '/verify')) }}
                        <div class="form-group">
                            {{ Form::submit('Resend Verification Email', array('class' => 'btn btn-primary btn-md')) }}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop