<div class="form-group {{ $name }}">
    {{ Form::label($name, $text) }}
    {{ Form::textarea($name, $value, $options) }}
    @if (isset($errors))
    <p class="text-danger">{{ $errors->first($name) }}</p>
    @endif
</div>