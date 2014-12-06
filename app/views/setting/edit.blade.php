@extends('layouts.master')

@section('PlaceHolderTitle', 'Edit Setting')

@section('PlaceHolderMainForm')

    <h3>
        <a href="{{ URL::action('AppController@index') }}">Apps</a>
        <span> / </span>
        <a href="{{ URL::action('AppController@show', $setting->config->app->id) }}">{{{ $setting->config->app->name }}}</a>
        <span> / </span>
        <a href="{{ URL::action('ConfigController@index', $setting->config->app->id) }}">Configurations</a>
        <span> / </span>
        <a href="{{ URL::action('ConfigController@show', [$setting->config->app->id, $setting->config->id]) }}">{{{ $setting->config->name }}}</a>
        <span> / </span>
        <a href="{{ URL::action('SettingController@index', [$setting->config->app->id, $setting->config->id]) }}">Settings</a>
        <span>/</span>
        <a href="{{ URL::action('SettingController@show', [$setting->config->app->id, $setting->config->id, $setting->id]) }}">{{{ $setting->key }}}</a>
        <span>/</span>
        <span>Edit</span>
    </h3>

    {{ Form::model($setting, ['method' => 'put', 'action' => ['SettingController@update', $setting->config->app->id, $setting->config->id, $setting->id]]) }}
        <div class="form-group key">
            {{ Form::label('key', 'Key') }}
            {{ Form::text('key', $setting->key, array('class' => 'form-control')) }}
            <p class="text-danger">{{ $errors->first('key') }}</p>
        </div>
        <div class="form-group type">
            {{ Form::label('type', 'Type') }}
            {{ Form::select('type',
                array(
                    'binary' => 'Binary',
                    'boolean' => 'Boolean',
                    'datetime' => 'Datetime',
                    'integer' => 'Integer',
                    'string' => 'String'
                ),
                $setting->type,
                array('class' => 'form-control')
            ) }}
            {{ $setting->type }}
        </div>
        <div class="form-group value">
            {{ Form::label('value', 'Value') }}
            <div class="binary" style="display:none;">
                <span>{{{ $setting->value }}}</span>
                {{ Form::file('binary_value') }}
                <p class="text-danger">{{ $errors->first('binary_value') }}</p>
            </div>
            <div class="boolean" style="display:none;">
                {{ Form::checkbox('boolean_value', $setting->value, false) }}
                <p class="text-danger">{{ $errors->first('boolean_value') }}</p>
            </div>
            <div class="datetime" style="display:none;">
                {{ Form::text('datetime_value', $setting->value, array('class' => 'form-control')) }}
                <p class="text-danger">{{ $errors->first('datetime_value') }}</p>
            </div>
            <div class="integer" style="display:none;">
                {{ Form::number('integer_value', $setting->value, array('class' => 'form-control')) }}
                <p class="text-danger">{{ $errors->first('integer_value') }}</p>
            </div>
            <div class="string">
                {{ Form::textarea('string_value', $setting->value, array('class' => 'form-control')) }}
                <p class="text-danger">{{ $errors->first('string_value') }}</p>
            </div>
            
        </div>
        
        <div class="form-group">
            {{ Form::submit('Save', array('class' => 'btn btn-primary btn-md')) }}
            <a href="{{ URL::action('SettingController@show', [$setting->config->app->id, $setting->config->id, $setting->id]) }}" class="btn btn-default btn-md">Cancel</a>
        </div>
	{{ Form::close() }}
    
    <script>
    
        function setType(type) {
            $('.value .binary').val('').hide();
            $('.value .datetime').val('').hide();
            $('.value .boolean').prop('checked', false).hide();
            $('.value .integer').val('').hide();
            $('.value .string').val('').hide();

            $('.value .' + type).fadeIn();
        }
    
        $(document).ready(function() {
            setType($('select[name="type"]').val());
        
            $('select[name="type"]').change(function() {
                setType($(this).val());
            });
        });
    </script>
@stop