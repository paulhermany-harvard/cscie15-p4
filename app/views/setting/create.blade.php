@extends('layouts.master')

@section('PlaceHolderTitle', 'Create Setting')

@section('PlaceHolderMainForm')

    {{ HTML::breadcrumbs($config->breadcrumbs(), array(['Settings' => URL::action('SettingController@index', [$config->app->id, $config->id])]), ['Create']) }}
    <hr />
    
	{{ Form::open(array('action' => array('SettingController@store', $config->app->id, $config->id), 'files' => true)) }}
        <div class="row">
            <div class="col-md-6">
                {{ Form::control('text', 'key', 'Key', Input::get('key'), $errors) }}

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
                        null !== Input::get('type') ? Input::get('type') : 'string',
                        array('class' => 'form-control')
                    ) }}
                </div>
                <div class="form-group value">
                    {{ Form::label('value', 'Value') }}
                    <div class="binary" style="display:none;">
                        {{ Form::file('binary_value') }}
                        <p class="text-danger">{{ $errors->first('binary_value') }}</p>
                    </div>
                    <div class="boolean" style="display:none;">
                        {{ Form::checkbox('boolean_value', null, false) }}
                        <p class="text-danger">{{ $errors->first('boolean_value') }}</p>
                    </div>
                    <div class="datetime" style="display:none;">
                        {{ Form::text('datetime_value', null, array('class' => 'form-control')) }}
                        <p class="text-danger">{{ $errors->first('datetime_value') }}</p>
                    </div>
                    <div class="integer" style="display:none;">
                        {{ Form::number('integer_value', null, array('class' => 'form-control')) }}
                        <p class="text-danger">{{ $errors->first('integer_value') }}</p>
                    </div>
                    <div class="string">
                        {{ Form::textarea('string_value', null, array('class' => 'form-control')) }}
                        <p class="text-danger">{{ $errors->first('string_value') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            {{ Form::submit('Save', array('class' => 'btn btn-primary btn-md')) }}
            <a href="{{ URL::action('SettingController@index', [$config->app->id, $config->id]) }}" class="btn btn-default btn-md">Cancel</a>
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