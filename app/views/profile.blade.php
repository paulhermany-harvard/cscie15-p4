@extends('layouts.master')

@section('PlaceHolderTitle', 'Profile')

@section('PlaceHolderMainForm')
    <div class="row">
        <div class="col-md-12">
            <h4>
                {{{ $user->email_display }}}
            </h4>
        </div>
    </div>
    
    
        <div class="row">
            <div class="col-md-12">
            {{ Form::open(array('url' => url('/profile'), 'class' => 'form-inline')) }}
                {{ Form::label('api_token', 'API Token', ['class' => 'sr-only']) }}
                {{ Form::text('api_token', $user->api_token, ['class' => 'form-control', 'disabled' => 'disabled']) }}
                {{ Form::submit('Generate', [
                    'class' => 'btn btn-default btn-md'
                ]) }}
            {{ Form::close() }}
            
                @if($user->confirmed)
                    <a href="/verify">Verify</a>
                @endif
                
            </div>
            <div class="col-md-4">
                
            </div>
        </div>
    
    
@stop