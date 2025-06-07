<?php

function mp_generate_tldr_css_vars(array $styles = []): string {
    // Resolve each field with fallback logic: mobile uses desktop if missing, and vice versa.
    $fields = [
        'padding'       => ['type' => 'box', 'unit' => 'rem'],
        'margin'        => ['type' => 'box', 'unit' => 'rem'],
        'border_width'  => ['type' => 'box', 'unit' => 'px'],
        'border_radius' => ['type' => 'box', 'unit' => 'rem'],
        'title_size'    => ['type' => 'font_size'],
        'font_size'     => ['type' => 'font_size'],
        'font_weight'   => ['type' => 'font_weight'],
        'title_color'   => ['type' => 'color'],
        'text_color'    => ['type' => 'color'],
        'border_color'  => ['type' => 'color'],
        'background'    => ['type' => 'color'],
    ];

    $desktop_vars = [];
    $mobile_vars = [];

    foreach ($fields as $key => $meta) {
        $desktop = $styles[$key]['desktop'] ?? ($meta['type'] === 'font_weight' || $meta['type'] === 'color' ? ($styles[$key]['desktop'] ?? '') : []);
        $mobile  = $styles[$key]['mobile']  ?? ($meta['type'] === 'font_weight' || $meta['type'] === 'color' ? ($styles[$key]['mobile']  ?? '') : []);

        // Fallback logic
        $has_desktop = is_array($desktop) ? array_filter($desktop) : !empty($desktop);
        $has_mobile  = is_array($mobile) ? array_filter($mobile) : !empty($mobile);

        if (!$has_desktop) $desktop = $mobile;
        if (!$has_mobile)  $mobile  = $desktop;

        // Resolve values
        switch ($meta['type']) {
            case 'box':
                $desktop_vars[] = "--mp-{$key}: " . mp_resolve_box_value($desktop, $meta['unit']) . ';';
                $mobile_vars[]  = "--mp-{$key}-mobile: " . mp_resolve_box_value($mobile, $meta['unit']) . ';';
                break;

            case 'font_size':
                $desktop_vars[] = "--mp-{$key}: " . mp_resolve_font_size($desktop) . ';';
                $mobile_vars[]  = "--mp-{$key}-mobile: " . mp_resolve_font_size($mobile) . ';';
                break;

            case 'font_weight':
                $desktop_vars[] = "--mp-font-weight: " . mp_resolve_font_weight($desktop) . ';';
                $mobile_vars[]  = "--mp-font-weight-mobile: " . mp_resolve_font_weight($mobile) . ';';
                break;

            case 'color':
                $is_responsive = !empty($styles[$key]['responsive']);

                if ($is_responsive) {
                    $desktop = $styles[$key]['desktop'] ?? '';
                    $mobile = $styles[$key]['mobile'] ?? '';

                    // fallback
                    if (!$desktop) $desktop = $mobile;
                    if (!$mobile)  $mobile = $desktop;
                } else {
                    $desktop = $mobile = $styles[$key]['value'] ?? '';
                }

                $desktop_vars[] = "--mp-{$key}: " . mp_resolve_color($desktop) . ';';
                $mobile_vars[]  = "--mp-{$key}-mobile: " . mp_resolve_color($mobile) . ';';
                break;

        }
    }

    $css = ".tldr-summary {\n    " . implode("\n    ", $desktop_vars) . "\n}\n";
    $css .= "@media (max-width: 768px) {\n    .tldr-summary {\n        " . implode("\n        ", $mobile_vars) . "\n    }\n}";

    return $css;
}
