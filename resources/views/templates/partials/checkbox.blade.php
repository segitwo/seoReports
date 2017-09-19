<label class="checkbox" style="margin: 0">
    @if(isset($disabled))
        {{ $disabled = 'disabled => disabled' }}
    @else
        {{ $disabled = '' }}
    @endif
    {!! Form::hidden($propertyName . '[' . $property . ']', 0) !!}
    {!! Form::checkbox($propertyName . '[' . $property . ']', 1, $value or 0, ['data-toggle' => 'checkbox', $disabled]) !!}
    @lang('templates.checkbox_' . $property)
</label>