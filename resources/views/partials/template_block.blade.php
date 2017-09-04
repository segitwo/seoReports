<li data-name="{{ $class_key }}">
    <input type="hidden" name="blocks[]" value="{{ $class_key }}" disabled="disabled">
    <div class="templateBlock">
        <div class="title">@lang('templates.' . $class_key )</div>
        <div class="actions">
            <span class="removeBlock fui-cross"></span>
        </div>
    </div>
</li>