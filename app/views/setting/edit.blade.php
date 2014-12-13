@extends('layouts.master')

@section('PlaceHolderTitle', 'Edit Setting')

@section('PlaceHolderMainForm')

    {{ HTML::breadcrumbs(null, $setting->breadcrumbs(), ['Edit']) }}

    {{ Form::model($setting, ['method' => 'put', 'action' => ['SettingController@update', $setting->config->app->id, $setting->config->id, $setting->id]]) }}
        <div class="row">
            <div class="col-md-6">
                {{ Form::control('text', 'key', 'Key', $setting->key, $errors) }}
                        
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
                </div>
                <div class="form-group value">
                    {{ Form::label('value', 'Value') }}
                    <div class="binary" style="display:none;">
                        <span>{{{ $setting->value }}}</span>
                        {{ Form::file('binary_value') }}
                        <p class="text-danger">{{ $errors->first('binary_value') }}</p>
                    </div>
                    <div class="boolean" style="display:none;">
                        {{ Form::checkbox('boolean_value', null, $setting->value) }}
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
            </div>
            <div class="col-md-6">
                {{ Form::control('timestamp', 'created_at', 'Created', $setting->created_at) }}
                {{ Form::control('timestamp', 'updated_at', 'Updated', $setting->updated_at) }}
            </div>
        </div>
        <hr />
        
        <div class="form-group">
            {{ Form::submit('Update', [
                'class' => 'btn btn-primary btn-md'
            ]) }}
            {{ Form::link('Cancel', URL::action('SettingController@index', [$setting->config->app->id, $setting->config->id]), [
                'class' => 'btn btn-default btn-md'
            ]) }}
            {{ Form::button('Delete', [
                'class' => 'btn btn-default btn-md',
                'data-action' => URL::action('SettingController@destroy', [$setting->config->app->id, $setting->config->id, $setting->id]),
                'data-target' => '#confirm-delete',
                'data-title' => $setting->key,
                'data-toggle' => 'modal'
            ]) }}
        </div>
	{{ Form::close() }}
    
    {{ HTML::confirm_delete('Delete', Lang::get('api.setting_delete_warning')) }}
    
    <script>
        function setType(type) {
            $('.value .binary').hide();
            $('.value .datetime').hide();
            //.prop('checked', false)
            $('.value .boolean').hide();
            $('.value .integer').hide();
            $('.value .string').hide();
            
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