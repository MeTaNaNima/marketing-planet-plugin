<?php
function mp_render_box_field($option_name, $device, $label, $data, $default_unit = 'rem') {
    $mode = $data['mode'] ?? 'standard';
    $value = $data['value'] ?? '';
    $unit = $data['unit'] ?? $default_unit;
    $sides = $data['sides'] ?? ['top' => '', 'right' => '', 'bottom' => '', 'left' => ''];

    echo "<tr><th scope='row'>{$label}</th><td>";

    // Mode select
    echo "<select name='{$option_name}[{$device}][mode]' class='mp-mode-toggle'>";
    echo "<option value='standard'" . selected($mode, 'standard', false) . ">Standard (Top/Right/Bottom/Left)</option>";
    echo "<option value='custom'" . selected($mode, 'custom', false) . ">Custom</option>";
    echo "</select><br><br>";

    // Standard mode (4 sides + unit)
    $display_std = $mode === 'standard' ? '' : 'style="display:none;"';
    echo "<div class='mp-standard-box' {$display_std}>";
    foreach (['top', 'right', 'bottom', 'left'] as $side) {
        $val = esc_attr($sides[$side] ?? '');
        echo "<div class='mp-input-box'>";
        echo ucfirst($side) . ": <input type='number' step='any' name='{$option_name}[{$device}][sides][{$side}]' value='{$val}' style='width:100px;' />";
        echo "</div>";
    }

    echo "<div class='mp-input-box'>";
    echo " Unit: <select name='{$option_name}[{$device}][unit]'>";
    foreach (['rem', 'px', 'em', '%'] as $u) {
        echo "<option value='{$u}' " . selected($unit, $u, false) . ">{$u}</option>";
    }
    echo "</select>";
    echo "</div>";
    echo "</div>";

    // Custom mode
    $display_custom = $mode === 'custom' ? '' : 'style="display:none;"';
    echo "<div class='mp-custom-box' {$display_custom}>";
    echo "<input type='text' name='{$option_name}[{$device}][value]' value='{$value}' style='width:25rem;' />";
    echo "</div>";

    echo '</td></tr>';
}


function mp_render_font_size_field($option_name, $key, $label, $data = []): void
{
    $size = $data['size'] ?? '';
    $unit = $data['unit'] ?? 'px';

    echo "<tr><th scope='row'>{$label}</th><td>";
    echo "Size: <input type='number' step='0.1' name='{$option_name}[{$key}][size]' value='{$size}' style='width:100px;' />";
    echo " <select name='{$option_name}[{$key}][unit]'>";
    foreach (['px', 'rem', 'em', '%'] as $u) {
        echo "<option value='{$u}' " . selected($unit, $u, false) . ">{$u}</option>";
    }
    echo "</select>";
    echo "</td></tr>";
}

function mp_render_font_weight_field($option_name, $key, $label, $value = ''): void
{
    $weights = ['inherit', '100','200','300','400','500','600','700','800','900'];
    echo "<tr><th scope='row'>{$label}</th><td>";
    echo "<select name='{$option_name}[{$key}]'>";
    foreach ($weights as $weight) {
        echo "<option value='{$weight}' " . selected($value, $weight, false) . ">{$weight}</option>";
    }
    echo "</select></td></tr>";
}

function mp_render_color_field($option_name, $key, $label, $value = ''): void
{
    echo "<tr><th scope='row'>{$label}</th><td>";
    echo "<input type='color' name='{$option_name}[{$key}]' value='" . esc_attr($value ?: '#000000') . "' />";
    echo " <em>(Leave empty for 'inherit')</em>";
    echo "</td></tr>";
}
