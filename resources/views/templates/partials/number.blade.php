@if(isset($disabled))
    {{ $disabled = 'disabled => disabled' }}
@else
    {{ $disabled = '' }}
@endif
<div class="form-inline">
    <label for="disabledTextInput">@lang('templates.number_' . $property)</label>
    {!! Form::hidden($propertyName . '[' . $property . ']', 0) !!}
    {!! Form::number($propertyName . '[' . $property . ']', $value, ['class' => 'form-control input-sm', $disabled]) !!}
</div>