<label class="checkbox" style="margin: 0">
    {!! Form::hidden($propertyName . '[' . $property . ']', 0) !!}
    {!! Form::checkbox($propertyName . '[' . $property . ']', 1, $value or 0, ['data-toggle' => 'checkbox']) !!}
    @lang('templates.checkbox_' . $property)
</label>