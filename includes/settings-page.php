<?php

defined('ABSPATH') || exit;

/**
 * Register settings page.
 */
add_action('admin_menu', function () {
    add_menu_page(
        'Marketing Planet Settings',
        'MP Settings',
        'manage_options',
        'marketing-planet-settings',
        'marketing_planet_settings_page',
        'dashicons-admin-generic',
        81
    );
});

/**
 * Register settings.
 */
add_action('admin_init', function () {
    register_setting('marketing_planet_settings_group', 'marketing_planet_active_modules');
    register_setting('marketing_planet_settings_group', 'marketing_planet_tldr_post_types');
    register_setting('marketing_planet_settings_group', 'marketing_planet_styles_tldr');
    register_setting('marketing_planet_settings_group', 'marketing_planet_tldr_section_title');
    register_setting('marketing_planet_settings_group', 'marketing_planet_faq_post_types');
    register_setting('marketing_planet_settings_group', 'marketing_planet_faq_auto_append');

    add_action('update_option_marketing_planet_styles_tldr', function ($old, $new) {
        $css = mp_generate_tldr_css_vars($new);

        $upload_dir = wp_upload_dir();
        $dir = trailingslashit($upload_dir['basedir']) . 'marketing-planet/tldr-field/';
        $file = $dir . 'tldr-field.css';

        if (!file_exists($dir)) {
            wp_mkdir_p($dir);
        }

        file_put_contents($file, $css);
    }, 10, 2);
});

function marketing_planet_settings_page(): void
{
    $modules_dir = plugin_dir_path(__DIR__) . 'modules/';
    $module_folders = glob($modules_dir . '*', GLOB_ONLYDIR);
    $active_modules = (array) get_option('marketing_planet_active_modules', []);
    $tldr_enabled_post_types = (array) get_option('marketing_planet_tldr_post_types', []);
    $faq_enabled_post_types = (array) get_option('marketing_planet_faq_post_types', []);
    $faq_auto_append = get_option('marketing_planet_faq_auto_append', false);
    $tldr_section_title = get_option('marketing_planet_tldr_section_title', 'Here is the Quick Answer:');
    $tldr_styles = get_option('marketing_planet_styles_tldr', []);
    $post_types = array_filter(
        get_post_types(['public' => true], 'objects'),
        fn($type) => !in_array($type->name, ['attachment', 'page', 'e-floating-buttons', 'elementor_library'])
    );

    echo '<div class="wrap"><h1>Marketing Planet Plugin</h1>';

    echo '<h2 class="nav-tab-wrapper">';
    echo '<a href="#" class="nav-tab nav-tab-active" data-tab="tab-modules">Activate Modules</a>';
    if (in_array('tldr-field', $active_modules)) echo '<a href="#" class="nav-tab" data-tab="tab-tldr">TL;DR Settings</a>';
    if (in_array('faq-repeater', $active_modules)) echo '<a href="#" class="nav-tab" data-tab="tab-faq">FAQ Settings</a>';
    echo '<a href="#" class="nav-tab" data-tab="tab-styles">Add/Edit Styles</a>';
    echo '<a href="#" class="nav-tab" data-tab="tab-about">About</a>';
    echo '</h2>';

    echo '<form method="post" action="options.php">';
    settings_fields('marketing_planet_settings_group');

    echo '<div class="tab-section" id="tab-modules">';
    echo '<h2>Enable Modules</h2><table class="form-table"><tbody>';
    $module_titles = $GLOBALS['marketing_planet_module_titles'] ?? [];
    foreach ($module_folders as $folder) {
        $slug = basename($folder);
        $label = $module_titles[$slug] ?? ucwords(str_replace(['-', '_'], ' ', $slug));
        $checked = in_array($slug, $active_modules) ? 'checked' : '';
        echo "<tr><th scope='row'>{$label}</th><td><label><input type='checkbox' name='marketing_planet_active_modules[]' value='{$slug}' {$checked}> Enable</label></td></tr>";
    }
    echo '</tbody></table></div>';

    if (in_array('tldr-field', $active_modules)) {
        echo '<div class="tab-section" id="tab-tldr" style="display:none;">';
        echo '<h2>TL;DR Settings</h2>';
        echo '<table class="form-table"><tr><th scope="row">Post Types</th><td>';
        echo '<select name="marketing_planet_tldr_post_types[]" multiple size="3" style="min-width:250px;">';
        foreach ($post_types as $type) {
            $selected = in_array($type->name, $tldr_enabled_post_types) ? 'selected' : '';
            echo "<option value='{$type->name}' {$selected}>{$type->label}</option>";
        }
        echo '</select></td></tr>';
        echo '<tr><th scope="row">Section Title</th><td>';
        echo '<input type="text" name="marketing_planet_tldr_section_title" value="' . esc_attr($tldr_section_title) . '" style="min-width: 250px;">';
        echo '<p class="description">Leave empty to use the default: "Here is the Quick Answer:"</p>';
        echo '</td></tr></table></div>';
    }

    if (in_array('faq-repeater', $active_modules)) {
        echo '<div class="tab-section" id="tab-faq" style="display:none;">';
        echo '<h2>FAQ Settings</h2><table class="form-table">';
        echo '<tr><th scope="row">Post Types</th><td>';
        echo '<select name="marketing_planet_faq_post_types[]" multiple size="3" style="min-width:250px;">';
        foreach ($post_types as $type) {
            $selected = in_array($type->name, $faq_enabled_post_types) ? 'selected' : '';
            echo "<option value='{$type->name}' {$selected}>{$type->label}</option>";
        }
        echo '</select></td></tr>';
        echo '<tr><th scope="row">Auto Append</th><td>';
        echo '<label><input type="checkbox" name="marketing_planet_faq_auto_append" value="1"' . ($faq_auto_append ? ' checked' : '') . '> Automatically append FAQ after post content</label>';
        echo '<p>Manual usage: <code>[mp_faqs]</code></p>';
        echo '</td></tr></table></div>';
    }

    echo '<div class="tab-section" id="tab-styles" style="display:none;">';
    if (in_array('tldr-field', $active_modules)) {
        echo '<h3>TL;DR Box Styles</h3><table class="form-table">';
        mp_render_box_field_dual('marketing_planet_styles_tldr', 'padding', 'Padding', $tldr_styles['padding'] ?? []);
        mp_render_box_field_dual('marketing_planet_styles_tldr', 'margin', 'Margin', $tldr_styles['margin'] ?? []);
        mp_render_box_field_dual('marketing_planet_styles_tldr', 'border_width', 'Border Width', $tldr_styles['border_width'] ?? [], 'px');
        mp_render_font_size_field_dual('marketing_planet_styles_tldr', 'font_size', 'Font Size', $tldr_styles['font_size'] ?? []);
        mp_render_font_weight_field_dual('marketing_planet_styles_tldr', 'font_weight', 'Font Weight', $tldr_styles['font_weight'] ?? []);
        mp_render_color_field_responsive('marketing_planet_styles_tldr', 'text_color', 'Text Color', $tldr_styles['text_color'] ?? []);
        mp_render_color_field_responsive('marketing_planet_styles_tldr', 'border_color', 'Border Color', $tldr_styles['border_color'] ?? []);
        mp_render_color_field_responsive('marketing_planet_styles_tldr', 'background', 'Background Color', $tldr_styles['background'] ?? []);
        echo '</table>';
    } else {
        echo '<p><em>Activate the TL;DR module to configure its styles here.</em></p>';
    }
    echo '</div>';

    echo '<div class="tab-section" id="tab-about" style="display:none;">';
    echo '<p><strong>Marketing Planet Plugin</strong> is a modular toolset for reusable functionality, SEO, content enhancements, and more — built for performance-oriented WordPress development.</p>';
    echo '<p>Developed by <a href="https://marketingplanet.agency/" target="_blank">Marketing Planet Agency</a></p>';
    echo '</div>';

    submit_button('Save Changes');
    echo '</form>';

    echo <<<HTML
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tabs = document.querySelectorAll('.nav-tab');
                const sections = document.querySelectorAll('.tab-section');
            
                function showTab(id) {
                    tabs.forEach(t => t.classList.remove('nav-tab-active'));
                    sections.forEach(s => s.style.display = (s.id === id) ? 'block' : 'none');
                }
            
                tabs.forEach(tab => {
                    tab.addEventListener('click', function(e) {
                        e.preventDefault();
                        showTab(this.getAttribute('data-tab'));
                    });
                });
            
                // Real-time visibility toggle for module-related tabs
                const tldrCheckbox = document.querySelector('input[name="marketing_planet_active_modules[]"][value="tldr-field"]');
                const faqCheckbox = document.querySelector('input[name="marketing_planet_active_modules[]"][value="faq-repeater"]');
            
                const tldrTab = document.querySelector('[data-tab="tab-tldr"]');
                const faqTab = document.querySelector('[data-tab="tab-faq"]');
                const styleTab = document.querySelector('[data-tab="tab-styles"]');
            
                function toggleTabVisibility() {
                    if (tldrCheckbox) {
                        tldrTab?.style.setProperty('display', tldrCheckbox.checked ? '' : 'none');
                        document.getElementById('tab-tldr')?.style.setProperty('display', 'none');
                    }
                    if (faqCheckbox) {
                        faqTab?.style.setProperty('display', faqCheckbox.checked ? '' : 'none');
                        document.getElementById('tab-faq')?.style.setProperty('display', 'none');
                    }
                    if (styleTab) {
                        styleTab.style.setProperty('display', tldrCheckbox?.checked ? '' : '');
                    }
                }
            
                tldrCheckbox?.addEventListener('change', toggleTabVisibility);
                faqCheckbox?.addEventListener('change', toggleTabVisibility);
                toggleTabVisibility();
            });
        </script>
    HTML;
    echo '</div>';

}
