<?php


function mp_resolve_box_value(array $data, string $default_unit = 'rem'): string {
    $mode = $data['mode'] ?? 'standard';

    if ($mode === 'custom') {
        return trim($data['value'] ?? '') ?: 'inherit';
    }

    $sides = $data['sides'] ?? [];
    $unit  = $data['unit'] ?? $default_unit;

    $values = [];
    foreach (['top', 'right', 'bottom', 'left'] as $side) {
        $val = trim($sides[$side] ?? '');
        $values[] = ($val !== '' ? $val : '0') . $unit;
    }

    return implode(' ', $values);
}

function mp_resolve_font_size(array $data): string {
    $size = $data['size'] ?? '';
    $unit = $data['unit'] ?? 'px';

    if ($size === '') return 'inherit';
    return $size . $unit;
}

function mp_resolve_font_weight($value): string {
    return $value ?: 'inherit';
}

function mp_resolve_color($value): string {
    return $value ?: 'inherit';
}
