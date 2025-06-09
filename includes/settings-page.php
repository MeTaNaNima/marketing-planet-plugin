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
        'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODkiIGhlaWdodD0iODkiIHZpZXdCb3g9IjAgMCA4OSA3MSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTg1LjAwMjIgMi44MTE1OEM4Mi41MzU5IDEuODc1MTcgODAuMjE2IDEuMjk5NzkgNzcuNjI1OCAwLjgxNDY2Qzc1LjE4MjEgMC4zNjMzOCA3My41MjY2IDAuMjI4MDA0IDcxLjA4MjkgMC4wNDc0OTIzQzcwLjA2OTMgLTAuMDMxNDgxNyA2Ny4yNzY1IC0wLjAwODkyNTMxIDY2LjI1MTcgMC4wOTI2MTI3QzYzLjQ5MjYgMC4zNjMzODEgNTkuOTY3NyAwLjk1MDA0OCA1OC4xNjU5IDEuNDM1MTdDNTQuMDY2NyAyLjU0MDgxIDU0LjQyNzEgMi41Mjk1MyA1MS40MzE1IDMuNzU5MjZDNDcuNjM2NCA1LjMxNjE4IDQ0Ljc1MzQgNy4xNTUxNSA0MS4zNjM3IDkuNDU2NjhDMzkuNzc1OCAxMC41Mzk3IDM4LjIxMDQgMTEuNjQ1NCAzNi42NDUxIDEyLjczOTdDMzYuMDgyIDEyLjI5OTcgMzUuNTY0IDExLjg3MSAzNS4wMjM0IDExLjQ3NjJDMzIuOTQgOS45NjQzOCAzMC42OTkgOC43Njg0OCAyOC4yODkgNy44ODg0OEMyNy4wNjE1IDcuNDM3MiAyNS43Nzc3IDcuMjExNTYgMjQuNTE2NCA3LjA0MjMzQzIyLjM4OCA2Ljc2MDI4IDIwLjQxNzIgNy4wMTk3NyAxOC40OTE1IDcuNzg2OTVDMTYuNzEyMiA4LjQ5NzcxIDE1LjIzNjkgOS42NzEwMyAxMy44OTY3IDExLjAyNDlDMTAuMTU3OSAxNC44MTU2IDYuNDUyODcgMTguNjQwMiAyLjc0NzgyIDIyLjQ2NDhDMS45OTMzIDIzLjI0MzMgMS4yNzI1OCAyNC4wNTU2IDAuNTg1NjIyIDI0LjkwMTdDMC40MTY2OTkgMjUuMTE2MSAwLjI4MTUzOSAyNS4yOTY2IDAgMjUuNzE0QzQuNjg0OCAyNi4zOTEgOS41MDQ3NSAyNi41OTQgMTQuMjIzMyAyNy4yNTk3QzEyLjM3NjQgMjguNzAzOCAxMi4xMDYyIDI4LjkxODEgMTAuODExMSAyOS44OTk3QzExLjYxMDcgMzEuMzc3NiAxMi4zOTkgMzIuODMzIDEzLjE3NiAzNC4yNjU4QzE0LjQwMzUgMzQuOTQyNyAxNS41NzQ3IDM1LjU3NDUgMTYuNjc4MyAzNi4xODM3QzE4LjA1MjMgMzUuNzc3NiAxOS4yOTExIDM1LjQxNjYgMjAuNTQxMSAzNS4wNDQzQzIxLjgyNDkgMzQuNjcyIDIzLjEwODcgMzQuMjg4NCAyNC4zNTg3IDMzLjkyNzNDMjUuMjM3MSAzNC44NjM3IDMwLjg3OTIgNDQuNzEyOSAzMS4xMTU3IDQ1Ljc1MDlDMjkuMjM1IDQ3LjYyMzcgMjcuMzIwNSA0OS41MTkxIDI1LjQ1MTEgNTEuMzgwNlY1NS40NzZDMjYuMjczMiA1Ni44NTI0IDI3LjExNzggNTguMjczOSAyNy45ODUgNTkuNzI5M0wzMS43NDYzIDU4LjEzODVDMzAuNjUzOSA2MC41NDE2IDI5LjYyOTEgNjIuNzc1NCAyOC42MTU2IDY1LjAyMDVDMjcuODcyMyA2Ni42NTY0IDI3LjE0MDMgNjguMjkyMyAyNi40MzA5IDY5LjkzOTVDMjYuMzA3IDcwLjI0NDEgMjYuMTk0NCA3MC41MDM2IDI1Ljk4MDQgNzFDMjcuNTM0NSA3MC42ODQxIDI5LjE3ODcgNzAuNDQ3MiAzMC41NjM5IDcwLjEyQzMzLjcyODMgNjkuMzc1NCAzNi44OTI4IDY4LjYxOTUgNDAuMDQ2MSA2Ny44MDcyQzQyLjU5MTIgNjcuMTUyOCA0NS4xMzYzIDY2LjQ3NTkgNDcuNjI1MSA2NS42NTIzQzUwLjY4ODIgNjQuNjQ4MiA1Mi44NjE3IDYyLjUxNTkgNTQuMjI0MyA1OS42NTAzQzU1LjQ2MzEgNTcuMDMyOSA1Ni4wMDM3IDU0LjIwMTEgNTUuOTgxMSA1MS4zMDE2QzU1Ljk2OTkgNDkuNDczOSA1NS43ODk3IDQ3LjY1NzUgNTUuNjg4NCA0NS42ODMyQzU2LjYwMDUgNDUuMjU0NSA1Ny42MTQxIDQ0LjgwMzIgNTguNjE2NCA0NC4zMDY4QzYwLjkwMjUgNDMuMTY3MyA2My4yNTYxIDQyLjE0MDYgNjUuNDQwOSA0MC44MjA2QzY5Ljg0NDEgMzguMTU4MSA3NC4wNjcyIDM0Ljk2NTMgNzcuNTY5NSAzMS4xNzQ1Qzc5LjI0NzUgMjkuMzU4MSA4MC44MDE2IDI3LjQyODkgODIuMTQxNyAyNS4zNTNDODQuNTI5MiAyMS42NDEyIDg2LjQ2NjIgMTcuNzAzOCA4Ny42Mzc0IDEzLjQyNzlDODguMjY4IDExLjEzNzcgODguNDI1NyAxMC40NjA4IDg4LjgwODYgNy45NDQ4OUM4OC44NDI0IDcuNzMwNTQgODguODk4NiA2Ljk1MjA3IDg4LjkyMTIgNi43Mzc3MUM4OC45NDM3IDYuNTAwNzkgODguOTY2MiA2LjE2MjMzIDg4Ljk2NjIgNi4wNzIwOEw4OSA0LjcwNjk2Qzg3Ljc4MzggMy44MTU2OSA4Ni4zNzYxIDMuMzQxODMgODUuMDAyMiAyLjgxMTU4Wk02MS43Njk2IDI3LjczMzVDNTcuNTkxNiAyNy43MzM1IDU0LjIwMTggMjQuMzM3NiA1NC4yMDE4IDIwLjE1MkM1NC4yMDE4IDE1Ljk2NjQgNTcuNTkxNiAxMi41NzA1IDYxLjc2OTYgMTIuNTcwNUM2NS45NDc2IDEyLjU3MDUgNjkuMzM3NCAxNS45NjY0IDY5LjMzNzQgMjAuMTUyQzY5LjMzNzQgMjQuMzM3NiA2NS45NTg5IDI3LjczMzUgNjEuNzY5NiAyNy43MzM1WiIgZmlsbD0id2hpdGUiLz4KPC9zdmc+Cg==',
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
    register_setting('marketing_planet_settings_group', 'marketing_planet_github_token');
    register_setting('marketing_planet_settings_group', 'marketing_planet_faq_post_types');
    register_setting('marketing_planet_settings_group', 'marketing_planet_faq_auto_append');
    register_setting('marketing_planet_settings_group', 'marketing_planet_security_headers_method');
    register_setting('marketing_planet_settings_group', 'sitemap_excluded_post_types');

    add_action('admin_enqueue_scripts', function ($hook) {
        if ($hook === 'toplevel_page_marketing-planet-settings') {
            wp_enqueue_script('pickr', 'https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js', [], null, true);
            wp_enqueue_style('pickr-css', 'https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css'); // or nano, monolith
        }
    });



    register_setting('marketing_planet_settings_group', 'marketing_planet_custom_css');

    add_action('update_option_marketing_planet_custom_css', function ($old, $new) {
        $upload_dir = wp_upload_dir();
        $dir  = trailingslashit($upload_dir['basedir']) . 'marketing-planet/';
        $file = $dir . 'mp-front.css';

        if (!file_exists($dir)) {
            wp_mkdir_p($dir);
        }

        // Strip PHP tags for safety
        $css = trim(str_replace(['<?php', '?>'], '', $new));

        file_put_contents($file, $css, LOCK_EX);
    }, 10, 2);

    add_action('update_option_marketing_planet_styles_tldr', function ($old, $new) {
        $styles = is_array($new) ? $new : [];
        $css = mp_generate_tldr_css_vars($styles);

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
    $github_token = get_option('marketing_planet_github_token');
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
    echo '<a href="#" class="nav-tab" data-tab="tab-tldr" style="' . (in_array('tldr-field', $active_modules) ? '' : 'display:none;') . '">TL;DR Settings</a>';
    echo '<a href="#" class="nav-tab" data-tab="tab-faq" style="' . (in_array('faq-repeater', $active_modules) ? '' : 'display:none;') . '">FAQ Settings</a>';
    echo '<a href="#" class="nav-tab" data-tab="tab-security-headers" style="' . (in_array('security-headers', $active_modules) ? '' : 'display:none;') . '">Security Headers</a>';
    echo '<a href="#" class="nav-tab" data-tab="tab-sitemap-exclude" style="' . (in_array('sitemap-exclude-post-types', $active_modules) ? '' : 'display:none;') . '">Yoast Sitemap Control</a>';


    echo '<a href="#" class="nav-tab" data-tab="tab-styles">Add/Edit Styles</a>';
    echo '<a href="#" class="nav-tab" data-tab="tab-shortcodes">Shortcode Helper</a>';
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

        // Disable the checkbox for noindex-paginations if Yoast is not active
        // === CONDITIONAL MODULE DISABLING ===
        $disabled = '';
        $note = '';

        if ($slug === 'noindex-paginations' && !defined('WPSEO_VERSION')) {
            $disabled = 'disabled';
            $note = '<p class="description" style="color:#c00;">Requires Yoast SEO plugin.</p>';
        }

        if ($slug === 'extend-elementor-dynamic-fields' && !(did_action('elementor/loaded') && class_exists('\ElementorPro\Plugin'))) {
            $disabled = 'disabled';
            $note = '<p class="description" style="color:#c00;">Requires Elementor & Elementor Pro plugins.</p>';
        }

        echo "<tr><th scope='row'>{$label}</th><td><label><input type='checkbox' name='marketing_planet_active_modules[]' value='{$slug}' {$checked} {$disabled}> Enable</label>{$note}</td></tr>";
    }
    echo '</tbody></table>';

    echo '<hr>';
    echo '<pre>';
    print_r($module_folders);
    print_r($active_modules);
    echo '</pre>';
    echo '</div>';

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
        echo '<p>Manual usage: <code id="mp_faq_shortcode" title="Click on the shortcode to copy!" style="cursor:pointer;" onclick="copyMPShortcode()">[mp_faqs]</code> <span id="mp_faq_copy_feedback" style="display:none; color:green; margin-left:10px;">Copied!</span></p>';
        echo '</td></tr></table></div>';
    }

    if (in_array('security-headers', $active_modules)) {
        $method = get_option('marketing_planet_security_headers_method', 'htaccess');
        echo '<div class="tab-section" id="tab-security-headers" style="display:none;">';
        echo '<h2>Security Headers</h2>';
        echo '<table class="form-table">';
        echo '<tr><th scope="row">Header Injection Method</th><td>';
        echo '<select name="marketing_planet_security_headers_method">';
        echo '<option value="htaccess"' . selected($method, 'htaccess', false) . '>.htaccess (Default)</option>';
        echo '<option value="php"' . selected($method, 'php', false) . '>PHP (Fallback)</option>';
        echo '</select>';
        echo '<p class="description">Choose PHP if your server ignores .htaccess or uses NGINX.</p>';
        echo '</td></tr>';
        echo '</table>';
        echo '</div>';
    }


    echo '<div class="tab-section" id="tab-styles" style="display:none;">';

    echo '<h3>Custom CSS</h3>';
    echo '<p>Click to init Code editor.</p>';
    echo '<textarea id="mp-custom-css" name="marketing_planet_custom_css" rows="4" style="width:100%; font-family: monospace;">' . esc_textarea(get_option('marketing_planet_custom_css', '')) . '</textarea>';


    if (in_array('tldr-field', $active_modules)) {
        echo '<h3>TL;DR Box Styles</h3><table class="form-table">';
        mp_render_box_field_dual('marketing_planet_styles_tldr', 'padding', 'Padding', $tldr_styles['padding'] ?? []);
        mp_render_box_field_dual('marketing_planet_styles_tldr', 'margin', 'Margin', $tldr_styles['margin'] ?? []);
        mp_render_box_field_dual('marketing_planet_styles_tldr', 'border_width', 'Border Width', $tldr_styles['border_width'] ?? [], 'px');
        mp_render_box_field_dual('marketing_planet_styles_tldr', 'border_radius', 'Border Radius', $tldr_styles['border_radius'] ?? []);
        mp_render_font_size_field_dual('marketing_planet_styles_tldr', 'title_size', 'Title Size', $tldr_styles['title_size'] ?? []);
        mp_render_font_size_field_dual('marketing_planet_styles_tldr', 'font_size', 'Font Size', $tldr_styles['font_size'] ?? []);
        mp_render_font_weight_field_dual('marketing_planet_styles_tldr', 'font_weight', 'Font Weight', $tldr_styles['font_weight'] ?? []);
        mp_render_color_field_responsive('marketing_planet_styles_tldr', 'title_color', 'Title Color', $tldr_styles['title_color'] ?? []);
        mp_render_color_field_responsive('marketing_planet_styles_tldr', 'text_color', 'Text Color', $tldr_styles['text_color'] ?? []);
        mp_render_color_field_responsive('marketing_planet_styles_tldr', 'border_color', 'Border Color', $tldr_styles['border_color'] ?? []);
        mp_render_color_field_responsive('marketing_planet_styles_tldr', 'background', 'Background Color', $tldr_styles['background'] ?? []);
        echo '</table>';
    } else {
        echo '<p><em>Activate the TL;DR module to configure its styles here.</em></p>';
    }
    echo '</div>';

    if (in_array('sitemap-exclude-post-types', $active_modules)) {
        echo '<div class="tab-section" id="tab-sitemap-exclude" style="display:none;">';
        do_action('marketing_planet_module_settings_sitemap-exclude-post-types');
        echo '</div>';
    }

    echo '<div class="tab-section" id="tab-shortcodes" style="display:none;">';
    echo '<h2>Shortcode Helper</h2>';
    echo '<table class="widefat striped">';
    echo '<thead><tr><th>Shortcode</th><th>Description</th><th>Example</th></tr></thead>';
    echo '<tbody>';

    // mp_date shortcode
    if (in_array('mp-date-module', $active_modules)) {
        // echo to notice
    }
    echo '<tr>';
    echo '<td><code class="mp-copyable-shortcode" title="Click to copy" style="cursor:pointer;">[mp_date]</code></td>';
    echo '<td>Displays the current date using the default format (Y-m-d).</td>';
    echo '<td><code>[mp_date]</code> → 2025-06-08</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td><code class="mp-copyable-shortcode" title="Click to copy" style="cursor:pointer;">[mp_date format="F j, Y"]</code></td>';
    echo '<td>Displays a custom formatted date.</td>';
    echo '<td><code>[mp_date format="F j, Y"]</code> → June 8, 2025</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td><code class="mp-copyable-shortcode" title="Click to copy" style="cursor:pointer;">[mp_date format="H:i" timezone="Europe/Berlin"]</code></td>';
    echo '<td>Uses a specific timezone for the date output.</td>';
    echo '<td><code>[mp_date format="H:i" timezone="Europe/Berlin"]</code> → 16:42</td>';
    echo '</tr>';

    if (in_array('faq-repeater', $active_modules)) {
        //        echo to notice
    }
    // Example for FAQs
    echo '<tr>';
    echo '<td><code class="mp-copyable-shortcode" title="Click to copy" style="cursor:pointer;">[mp_faqs]</code></td>';
    echo '<td>Displays FAQs manually on the page.</td>';
    echo '<td><code class="mp-copyable-shortcode" title="Click to copy" style="cursor:pointer;">[mp_faqs]</code></td>';
    echo '</tr>';

    echo '</tbody>';
    echo '</table>';
    echo '<span id="mp_shortcode_copy_feedback" style="display:none; color:green; margin-top:10px;">Copied!</span>';
    echo '</div>';


    echo '<div class="tab-section" id="tab-about" style="display:none;">';
    echo '<p><strong>Marketing Planet Plugin</strong> is a modular toolset for reusable functionality, SEO, content enhancements, and more — built for performance-oriented WordPress development.</p>';
    echo '<p>Developed by <a href="https://marketingplanet.agency/" target="_blank">Marketing Planet Agency</a></p>';

    echo '<table class="form-table"><tr><th scope="row">Post Types</th><td>';
    echo '</td></tr>';
    echo '<tr><th scope="row">Plugin Token</th><td>';
    echo '<input type="password" name="marketing_planet_github_token" value="' . esc_attr($github_token) . '" class="regular-text" style="min-width: 250px;">';
    echo '<p class="description">Enter your GitHub personal access token to enable plugin updates from private repo!</p>';
    echo '</td></tr></table>';

    echo '</div>';

    submit_button('Save Changes');
    echo '</form>';

    wp_enqueue_code_editor(['type' => 'text/css']);
    wp_enqueue_script('wp-theme-plugin-editor', admin_url('js/code-editor.js'), ['jquery'], null, true);
    wp_enqueue_style('wp-codemirror');

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
                const securityHeadersCheckbox = document.querySelector('input[name="marketing_planet_active_modules[]"][value="security-headers"]');
                const securityHeadersTab = document.querySelector('[data-tab="tab-security-headers"]');
                const sitemapExcludeCheckbox = document.querySelector('input[name="marketing_planet_active_modules[]"][value="sitemap-exclude-post-types"]');
                const sitemapExcludeTab = document.querySelector('[data-tab="tab-sitemap-exclude"]');

                const tldrTabButton = document.querySelector('[data-tab="tab-tldr"]');
                const faqTabButton = document.querySelector('[data-tab="tab-faq"]');
                const securityHeadersTabButton = document.querySelector('[data-tab="tab-security-headers"]');
                const sitemapExcludeTabButton = document.querySelector('[data-tab="tab-sitemap-exclude"]');

                function toggleTabVisibility() {
                    if (tldrCheckbox) {
                        tldrTab?.style.setProperty('display', tldrCheckbox.checked ? '' : 'none');
                        tldrTabButton?.style.setProperty('display', tldrCheckbox.checked ? '' : 'none');
                    }
                    if (faqCheckbox) {
                        faqTab?.style.setProperty('display', faqCheckbox.checked ? '' : 'none');
                        faqTabButton?.style.setProperty('display', faqCheckbox.checked ? '' : 'none');
                    }
                    if (securityHeadersCheckbox) {
                        securityHeadersTab?.style.setProperty('display', securityHeadersCheckbox.checked ? '' : 'none');
                        securityHeadersTabButton?.style.setProperty('display', securityHeadersCheckbox.checked ? '' : 'none');
                    }
                    if (sitemapExcludeCheckbox) {
                        sitemapExcludeTab?.style.setProperty('display', sitemapExcludeCheckbox.checked ? '' : 'none');
                        sitemapExcludeTabButton?.style.setProperty('display', sitemapExcludeCheckbox.checked ? '' : 'none');
                    }
                }
            
                tldrCheckbox?.addEventListener('change', toggleTabVisibility);
                faqCheckbox?.addEventListener('change', toggleTabVisibility);
                securityHeadersCheckbox?.addEventListener('change', toggleTabVisibility);
                sitemapExcludeCheckbox?.addEventListener('change', toggleTabVisibility);
                
                toggleTabVisibility();
                
                if (typeof wp !== 'undefined' && wp.codeEditor) {
                    wp.codeEditor.initialize(document.getElementById('mp-custom-css'), {
                        codemirror: {
                            mode: 'css',
                            lineNumbers: true,
                            indentUnit: 4,
                            tabSize: 4,
                            autoCloseBrackets: true,
                            matchBrackets: true,
                            lineWrapping: true,
                            lint: true,
                        }
                    });
                }
                
                document.querySelectorAll('.mp-color-picker').forEach(el => {
                    const inputName = el.dataset.inputName;
                    const initialColor = el.dataset.initial || '#000000';
                    const hiddenInput = el.nextElementSibling;
            
                    const pickr = Pickr.create({
                        el: el,
                        theme: 'classic',
                        default: initialColor,
                        components: {
                            preview: true,
                            opacity: true,
                            hue: true,
                            interaction: {
                                hex: true,
                                rgba: true,
                                input: true,
                                clear: true,
                                save: true
                            }
                        }
                    });
            
                    pickr.on('save', (color) => {
                        const hex = color.toHEXA().toString();
                        hiddenInput.value = hex;
                        pickr.hide();
                    });
                });
                
                document.querySelectorAll('.mp-copyable-shortcode').forEach(el => {
                    el.addEventListener('click', () => {
                        navigator.clipboard.writeText(el.textContent).then(() => {
                            const feedback = document.getElementById('mp_shortcode_copy_feedback');
                            feedback.style.display = 'inline';
                            feedback.textContent = 'Copied!';
                            setTimeout(() => feedback.style.display = 'none', 1500);
                        });
                    });
                });
            });
            
            // Make copy function globally accessible
            window.copyMPShortcode = function () {
                const code = document.getElementById('mp_faq_shortcode');
                const text = code.textContent;
                navigator.clipboard.writeText(text).then(() => {
                    const feedback = document.getElementById('mp_faq_copy_feedback');
                    feedback.style.display = 'inline';
                    setTimeout(() => {
                        feedback.style.display = 'none';
                    }, 1500);
                });
            };
        </script>
    HTML;
    echo '</div>';

}
