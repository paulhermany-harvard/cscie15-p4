@extends('layouts.master')

@section('PlaceHolderTitle', 'Create Setting')

@section('PlaceHolderMainForm')

    <h3>
        <a href="{{ URL::action('AppController@index') }}">Apps</a>
        <span> / </span>
        <a href="{{ URL::action('AppController@show', $config->app->id) }}">{{{ $config->app->name }}}</a>
        <span> / </span>
        <a href="{{ URL::action('ConfigController@index', $config->app->id) }}">Configurations</a>
        <span> / </span>
        <a href="{{ URL::action('ConfigController@show', [$config->app->id, $config->id]) }}">{{{ $config->name }}}</a>
        <span> / </span>
        <a href="{{ URL::action('SettingController@index', [$config->app->id, $config->id]) }}">Settings</a>
        <span>/</span>
        <span>Create</span>
    </h3>

	{{ Form::open(array('action' => array('SettingController@store', $config->app->id, $config->id), 'files' => true)) }}
        <div class="form-group key">
            {{ Form::label('key', 'Key') }}
            {{ Form::text('key', null, array('class' => 'form-control')) }}
        </div>
        <div class="form-group type">
            {{ Form::label('type', 'Type') }}
            {{ Form::select('type',
                array(
                    'binary' => 'Binary',
                    'boolean' => 'Boolean',
                    'float' => 'Float',
                    'integer' => 'Integer',
                    'string' => 'String'
                ),
                Input::get('type'),
                array('class' => 'form-control')
            ) }}
        </div>
        <div class="form-group value">
            {{ Form::label('value', 'Value') }}
            <div class="binary" style="display:none;">
                {{ Form::file('binary_value') }}
            </div>
            <div class="boolean" style="display:none;">
                {{ Form::checkbox('boolean_value', null, false) }}
            </div>
            <div class="float" style="display:none;">
                {{ Form::text('float_value', null, array('class' => 'form-control')) }}
            </div>
            <div class="integer" style="display:none;">
                {{ Form::number('integer_value', null, array('class' => 'form-control')) }}
            </div>
            <div class="string">
                {{ Form::textarea('string_value', null, array('class' => 'form-control')) }}
            </div>            
        </div>
        
        <div class="form-group">
            <ul class="list-unstyled errors">
              @foreach($errors->all() as $message)
                <li><p class="text-danger">{{ $message }}</p></li>
              @endforeach
            </ul>
        </div>
        
        <div class="form-group">
            {{ Form::submit('Save', array('class' => 'btn btn-primary btn-md')) }}
            <a href="{{ URL::action('SettingController@index', [$config->app->id, $config->id]) }}" class="btn btn-default btn-md">Cancel</a>
        </div>
	{{ Form::close() }}
    
    <script>
    
        function setType(type) {
            $('.value .binary').val('').hide();
            $('.value .boolean').prop('checked', false).hide();
            $('.value .float').val('').hide();
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