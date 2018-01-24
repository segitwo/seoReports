<label class="checkbox" style="margin: 0">
    @php ($properties = ['data-toggle' => 'checkbox'])
    @if(isset($disabled))
        @php ($properties['disabled'] = 'disabled')
    @endif
    {!! Form::hidden($propertyName . '[' . $property . ']', 0) !!}
    {!! Form::checkbox($propertyName . '[' . $property . ']', 1, $value or 0, $properties) !!}
    @lang('templates.checkbox_' . $property)
</label>