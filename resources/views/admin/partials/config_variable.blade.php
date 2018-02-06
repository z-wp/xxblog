<?php
$variable_name = $variable['name'];
$type = isset($variable['type']) ? $variable['type'] : 'text';// default text
$default = isset($variable['default']) ? $variable['default'] : '';
$final_value = isset($$variable_name) ? $$variable_name : $default;
?>
<div class="form-group row">
    <label for="{{ $variable['name'] }}" class="col-sm-4 col-form-label">{{ $variable['label'] }}</label>
    <div class="col-sm-8">
        @if($type == 'textarea')
            <textarea id="{{ $variable_name }}" class="form-control autosize-target"
                      placeholder="{{ $variable['placeholder'] or '' }}"
                      rows="{{ $variable['rows'] or 3 }}"
                      name="{{ $variable_name }}">{{ $final_value }}</textarea>
        @else
            <input type="{{ $type }}" name="{{ $variable_name }}"
                   class="form-control" id="{{ $variable_name }}"
                   placeholder="{{ $variable['placeholder'] or '' }}"
                   value="{{ $final_value }}">
        @endif
    </div>
</div>