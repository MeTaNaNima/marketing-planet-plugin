<?php
function mp_render_box_field_dual($option_name, $key, $label, $data = [], $default_unit = 'rem'): void {
    $devices = [
        'desktop' => 'Desktop',
        'mobile' => 'Mobile'
    ];
    echo "<tr><th scope='row'>{$label}</th><td>";

    $mode = $data['mode'] ?? 'standard';
    echo "<div class='mp-box'>";
    echo "<div class='mp-box-content'>";
    echo "<select name='{$option_name}[{$key}][mode]' class='mp-mode-toggle'>";
    echo "<option value='standard'" . selected($mode, 'standard', false) . ">Standard</option>";
    echo "<option value='custom'" . selected($mode, 'custom', false) . " disabled>Custom</option>";
    echo "</select>";
    echo "</div>";
    echo "<div class='mp-box-content'>";
    echo "Unit: <select name='{$option_name}[{$key}][desktop][unit]'>";
    foreach (['rem', 'px', 'em', '%'] as $u) {
        echo "<option value='{$u}' " . selected(($data['desktop']['unit'] ?? $default_unit), $u, false) . ">{$u}</option>";
    }
    echo "</select></div></div></div>";

    $display_std = $mode === 'standard' ? '' : 'style="display:none;"';
    echo "<div class='mp-standard-box' {$display_std}><table><tbody><tr><td>";
    echo "<div class='mp-input-container'>";
    echo "<div class='mp-input-container-inner text-left'>";
    echo "<div class='mp-input-container-inner text-left'>Side</div>";
    echo "<div class='mp-input-container-inner text-left'>Desktop</div>";
    echo "<div class='mp-input-container-inner text-left'>Mobile</div>";
    echo "</div>";

    foreach (['top', 'right', 'bottom', 'left'] as $side) {
        echo "<div class='mp-input-container-inner'>";
            echo "" . ucfirst($side) . "";
            foreach ($devices as $device => $label) {
                $val = esc_attr($data[$device]['sides'][$side] ?? '');
                echo "<input type='number' step='any' name='{$option_name}[{$key}][{$device}][sides][{$side}]' value='{$val}' style='width:80px;' />";
            }
        echo "</div>";
    }
    echo "</div></td></tr></tbody></table>";


    $display_custom = $mode === 'custom' ? '' : 'style="display:none;"';
    echo "<div class='mp-custom-box' {$display_custom}>";
    foreach ($devices as $device => $label) {
        $val = esc_attr($data[$device]['value'] ?? '');
        echo "<label>{$label}: <input type='text' name='{$option_name}[{$key}][{$device}][value]' value='{$val}' style='width:200px;' /></label><br><br>";
    }
    echo "</div></td></tr>";
}

function mp_render_font_size_field_dual($option_name, $key, $label, $data = []): void {
    echo "<tr><th scope='row'>{$label}</th><td><table><thead><tr><th></th><th>Desktop</th><th>Mobile</th></tr></thead><tbody><tr><td>Size</td>";
    foreach (['desktop', 'mobile'] as $device) {
        $size = $data[$device]['size'] ?? '';
        $unit = $data[$device]['unit'] ?? 'rem';
        echo "<td><input type='number' step='0.05' name='{$option_name}[{$key}][{$device}][size]' value='{$size}' style='width:120px;' />";
        echo "<select name='{$option_name}[{$key}][{$device}][unit]'>";
        foreach (['rem', 'px', 'em', '%'] as $u) {
            echo "<option value='{$u}' " . selected($unit, $u, false) . ">{$u}</option>";
        }
        echo "</select></td>";
    }
    echo "</tr></tbody></table></td></tr>";
}

function mp_render_font_weight_field_dual($option_name, $key, $label, $data = []): void {
    $weights = ['inherit', '100', '200', '300', '400', '500', '600', '700', '800', '900'];
    echo "<tr><th scope='row'>{$label}</th><td><table><thead><tr><th></th><th>Desktop</th><th>Mobile</th></tr></thead><tbody><tr><td>Weight</td>";
    foreach (['desktop', 'mobile'] as $device) {
        $value = $data[$device] ?? 'inherit';
        echo "<td><select name='{$option_name}[{$key}][{$device}]'>";
        foreach ($weights as $weight) {
            echo "<option value='{$weight}' " . selected($value, $weight, false) . ">{$weight}</option>";
        }
        echo "</select></td>";
    }
    echo "</tr></tbody></table></td></tr>";
}

function mp_render_color_field_responsive($option_name, $key, $label, $data = []): void {
    $is_responsive = !empty($data['responsive']);

    echo "<tr><th scope='row'>{$label}</th><td>";

    // Toggle: Responsive or not
    // echo "<label><input type='checkbox' class='mp-responsive-toggle' data-target='{$key}' name='{$option_name}[{$key}][responsive]' value='1' " . checked($is_responsive, true, false) . "> Responsive?</label><br><br>";


    // Unified field
    $single_value = esc_attr($data['value'] ?? '');
    $display_single = !$is_responsive ? '' : 'style="display:none;"';
    echo "<div class='mp-color-single mp-responsive-{$key}' {$display_single}>";
    echo "<input type='color' name='{$option_name}[{$key}][value]' value='" . ($single_value ?: '#000000') . "' />";
    echo " <em>(Leave empty for 'inherit')</em>";
    echo "</div>";

    // Responsive (desktop/mobile)
    $display_dual = $is_responsive ? '' : 'style="display:none;"';
    echo "<div class='mp-color-dual mp-responsive-{$key}' {$display_dual}>";
    echo "<table><thead><tr><th></th><th>Desktop</th><th>Mobile</th></tr></thead><tbody><tr><td>Color</td>";
    foreach (['desktop', 'mobile'] as $device) {
        $val = esc_attr($data[$device] ?? '');
        echo "<td><input type='color' name='{$option_name}[{$key}][{$device}]' value='" . ($val ?: '#000000') . "' /></td>";
//        echo "<td><input type='color' name='{$option_name}[{$key}][{$device}]' value='" . ($val ?: '#000000') . "' />";


    }
    echo "</tr></tbody></table></div>";

    echo "</td></tr>";
}
