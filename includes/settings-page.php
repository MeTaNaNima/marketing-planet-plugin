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

    register_setting('marketing_planet_settings_group', 'marketing_planet_faq_post_types');
    register_setting('marketing_planet_settings_group', 'marketing_planet_faq_auto_append');




});

/**
 * Settings page HTML.
 */
function marketing_planet_settings_page(): void
{
    $modules_dir = plugin_dir_path(__DIR__) . 'modules/';
    $module_folders = glob($modules_dir . '*', GLOB_ONLYDIR);
    $active_modules = (array) get_option('marketing_planet_active_modules', []);
    $tldr_enabled_post_types = (array) get_option('marketing_planet_tldr_post_types', []);
    $post_types = array_filter(
        get_post_types(['public' => true], 'objects'),
        fn($type) => !in_array($type->name, ['attachment', 'page', 'e-floating-buttons', 'elementor_library'])
    );

    echo '<div class="wrap"><h1>Marketing Planet Plugin</h1>';

    // 🔹 Tabs
    echo '<h2 class="nav-tab-wrapper">';
    echo '<a href="#" class="nav-tab" data-tab="tab-about-plugin">About This Plugin</a>';
    echo '<a href="#" class="nav-tab nav-tab-active" data-tab="tab-modules">Activate Modules</a>';
    echo '<a href="#" class="nav-tab" data-tab="tab-styles">Add/Edit Styles</a>';
    echo '<a href="#" class="nav-tab" data-tab="tab-about-us">About Us</a>';
    echo '</h2>';

    // ✅ Only wrap "Modules" and "Styles" in the form
    echo '<form method="post" action="options.php">';
    settings_fields('marketing_planet_settings_group');

    submit_button('Save Changes');
    // 🔹 Tab: Activate Modules
    echo '<div class="tab-section" id="tab-modules">';

    echo '<h2>Enable Modules</h2><table class="form-table"><tbody>';
    foreach ($module_folders as $folder) {
        $slug = basename($folder);
        $checked = in_array($slug, $active_modules) ? 'checked' : '';
        echo "<tr><th scope='row'>{$slug}</th><td>";
        echo "<label><input type='checkbox' name='marketing_planet_active_modules[]' value='{$slug}' {$checked}> Enable</label>";
        echo "</td></tr>";
    }
    echo '</tbody></table>';

    // TL;DR field settings
    $tldr_visible = in_array('tldr-field', $active_modules);
    $style = $tldr_visible ? '' : 'style="display:none;"';
    echo '<h2>TL;DR Field — Select Post Types</h2>';
    echo '<p>Select which post types should include the TL;DR summary field.</p>';
    echo "<table class='form-table'><tr id='tldr-post-types' {$style}><th scope='row'>Post Types</th><td>";
    echo '<select name="marketing_planet_tldr_post_types[]" multiple size="5" style="min-width:250px;">';
    foreach ($post_types as $type) {
        $selected = (in_array($type->name, $tldr_enabled_post_types) || (empty($tldr_enabled_post_types) && $type->name === 'post')) ? 'selected' : '';
        echo "<option value='{$type->name}' {$selected}>{$type->label}</option>";
    }
    echo '</select></td></tr></table>';

    // FAQ Repeater field settings
    $faq_visible = in_array('faq-repeater', $active_modules);
    $faq_style = $faq_visible ? '' : 'style="display:none;"';
    $faq_enabled_post_types = (array) get_option('marketing_planet_faq_post_types', []);
    echo '<h2>FAQ Repeater — Select Post Types</h2>';
    echo '<p>Select which post types should include the FAQ repeater field.</p>';
    echo "<table class='form-table'><tr id='faq-post-types' {$faq_style}><th scope='row'>Post Types</th><td>";
    echo '<select name="marketing_planet_faq_post_types[]" multiple size="5" style="min-width:250px;">';
    foreach ($post_types as $type) {
        $selected = in_array($type->name, $faq_enabled_post_types) ? 'selected' : '';
        echo "<option value='{$type->name}' {$selected}>{$type->label}</option>";
    }
    echo '</select></td></tr></table>';

    $faq_auto_append = get_option('marketing_planet_faq_auto_append', false);
    $checked = $faq_auto_append ? 'checked' : '';
    echo '<tr><th scope="row">Auto-Append to Content</th><td>';
    echo "<label><input type='checkbox' name='marketing_planet_faq_auto_append' value='1' {$checked}> Automatically append FAQ after post content</label>";
    echo '</td></tr>';

    echo '<tr><th scope="row">Manual Usage</th><td>';
    echo '<code>[mp_faqs]</code> — You can place this anywhere inside post content to manually show the FAQs.';
    echo '</td></tr>';





    echo '</div>';

    // 🔹 Tab: Add/Edit Styles
    echo '<div class="tab-section" id="tab-styles" style="display:none;">';
    $tldr_styles = get_option('marketing_planet_styles_tldr', []);
    if (in_array('tldr-field', $active_modules)) {
        echo '<h3>TL;DR Box Styles</h3>';
        echo '<table class="form-table">';

        // Reusable box fields
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



    // ✅ Submit button only inside form
    submit_button('Save Changes');
    echo '</form>'; // ✅ CLOSE form here


    // 🔹 Tab: About Plugin
    echo '<div class="tab-section" id="tab-about-plugin" style="display:none;">';
    echo '<p><strong>Marketing Planet Plugin</strong> is a modular toolset for reusable functionality, SEO, content enhancements, and more — built for performance-oriented WordPress development.</p>';
    echo "<p>Developed by <a href='https://marketingplanet.agency/' target='_blank'>Marketing Planet Agency</a></p>";
    echo '</div>';

    // 🔹 Tab: About Us
    echo '<div class="tab-section" id="tab-about-us" style="display:none;">';
    echo '<p>Created by <strong>Marketing Planet</strong>, a web agency focused on scalable and maintainable WordPress solutions.</p>';
    echo '</div>';


    // JS
    echo <<<HTML
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Tabs
            const tabs = document.querySelectorAll('.nav-tab');
            const sections = document.querySelectorAll('.tab-section');
            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    tabs.forEach(t => t.classList.remove('nav-tab-active'));
                    this.classList.add('nav-tab-active');
                    const id = this.getAttribute('data-tab');
                    sections.forEach(s => s.style.display = (s.id === id) ? 'block' : 'none');
                });
            });

            // TL;DR Toggle
            const tldrCheckbox = document.querySelector('input[name="marketing_planet_active_modules[]"][value="tldr-field"]');
            const tldrSettingsSection = document.getElementById('tldr-post-types');
            function toggleTldrVisibility() {
                if (tldrCheckbox && tldrCheckbox.checked) {
                    tldrSettingsSection.style.display = 'table-row';
                } else {
                    tldrSettingsSection.style.display = 'none';
                }
            }
            if (tldrCheckbox && tldrSettingsSection) {
                toggleTldrVisibility();
                tldrCheckbox.addEventListener('change', toggleTldrVisibility);
            }
            
            // FAQ Toggle
            const faqCheckbox = document.querySelector('input[name="marketing_planet_active_modules[]"][value="faq-repeater"]');
            const faqSettingsSection = document.getElementById('faq-post-types');
            function toggleFaqVisibility() {
                if (faqCheckbox && faqCheckbox.checked) {
                    faqSettingsSection.style.display = 'table-row';
                } else {
                    faqSettingsSection.style.display = 'none';
                }
            }
            if (faqCheckbox && faqSettingsSection) {
                toggleFaqVisibility();
                faqCheckbox.addEventListener('change', toggleFaqVisibility);
            }

            
            document.querySelectorAll('.mp-mode-toggle').forEach(select => {
                select.addEventListener('change', function () {
                    const container = this.closest('td');
                    container.querySelector('.mp-standard-box').style.display = (this.value === 'standard') ? '' : 'none';
                    container.querySelector('.mp-custom-box').style.display = (this.value === 'custom') ? '' : 'none';
                });
            });
        });
    </script>
    HTML;

    echo '</form></div>';
}


