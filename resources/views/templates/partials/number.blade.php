@php ($properties = ['class' => 'form-control input-sm'])
@if(isset($disabled))
    @php ($properties['disabled'] = 'disabled')
@endif
<div class="form-inline">
    <label for="disabledTextInput">@lang('templates.number_' . $property)</label>
    {!! Form::hidden($propertyName . '[' . $property . ']', 0) !!}
    {!! Form::number($propertyName . '[' . $property . ']', $value, $properties) !!}
</div>