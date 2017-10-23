<li data-name="{{ class_basename($block) }}" class="{{ $class or '' }}" >

    <input type="hidden" name="blocks[]" value="{{ class_basename($block) }}" {{ $disabled or '' }}>
    <div class="templateBlock panel">
        <div class="panel-heading title">
            <div class="title">@lang('templates.' . class_basename($block))</div>
            <div class="actions">
                @if(count($block->listProperties()))<span class="toggleProperties fui-triangle-down mrs"></span>@endif
                <span class="removeBlock fui-cross text-danger"></span>
            </div>
        </div>
        @if(count($block->listProperties()))
            <div class="panel-body">
                @foreach($block->getProperties() as $key => $property)

                    @if($property['type'] == 'boolean')
                        @include('templates.partials.checkbox', ['propertyName' => class_basename($block), 'property' => $key, 'value' => $property['value']])
                    @elseif($property['type'] == 'integer')
                        @include('templates.partials.number', ['propertyName' => class_basename($block), 'property' => $key, 'value' => $property['value']])
                    @elseif($property['type'] == 'select')
                        @include('templates.partials.select', ['propertyName' => class_basename($block), 'property' => $key, 'value' => $property['value'], 'values' => $property['values']])
                    @endif
                @endforeach
            </div>
        @endif

    </div>
</li>



