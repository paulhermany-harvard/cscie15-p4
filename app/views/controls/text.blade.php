<div class="form-group {{ $name }}">
    {{ Form::label($name, $text) }}
    {{ Form::text($name, $value, $options) }}
    @if (isset($errors))
    <p class="text-danger">{{ $errors->first($name) }}</p>
    @endif
</div>