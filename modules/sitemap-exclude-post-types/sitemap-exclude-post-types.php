<?php
// modules/class-sitemap-exclude-post-types.php

$GLOBALS['marketing_planet_module_titles']['sitemap-exclude-post-types'] = 'Exclude Post Types from Yoast Sitemap';

class SitemapExcludePostTypesModule {
    private $yoast_active;

    public function __construct() {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        $this->yoast_active = is_plugin_active('wordpress-seo/wp-seo.php') || is_plugin_active('wordpress-seo-premium/wp-seo-premium.php');

        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_notices', [$this, 'yoast_missing_notice']);

        // Only hook if Yoast is active
        if ($this->yoast_active) {
            add_filter('wpseo_sitemap_exclude_post_type', [$this, 'exclude_selected_post_types'], 10, 2);
        }
    }

    public function register_settings(): void {
        register_setting('marketing_planet_settings_group', 'sitemap_excluded_post_types');

        // Disable field if Yoast is missing
        if (!$this->yoast_active) {
            add_filter('pre_option_sitemap_excluded_post_types', '__return_empty_array');
        }

        // Inject into settings UI if module is active
        add_action('marketing_planet_module_settings_sitemap-exclude-post-types', [$this, 'render_settings']);
    }

    public function render_settings(): void {
        $selected = (array) get_option('sitemap_excluded_post_types', []);
        $post_types = get_post_types(['public' => true], 'objects');
        echo '<h2>Exclude Post Types from Yoast Sitemap</h2>';
        echo '<table class="form-table"><tr><th scope="row">Post Types</th><td>';
        echo '<select name="sitemap_excluded_post_types[]" multiple size="5" style="min-width:250px;" ' . (!$this->yoast_active ? 'disabled' : '') . '>';
        foreach ($post_types as $type) {
            $isSelected = in_array($type->name, $selected) ? 'selected' : '';
            echo "<option value='{$type->name}' {$isSelected}>{$type->label}</option>";
        }
        echo '</select>';
        if (!$this->yoast_active) {
            echo '<p class="description" style="color:#c00;">Requires Yoast SEO plugin.</p>';
        }
        echo '</td></tr></table>';
    }

    public function exclude_selected_post_types($excluded, $post_type): bool {
        $excluded_post_types = (array) get_option('sitemap_excluded_post_types', []);
        return in_array($post_type, $excluded_post_types, true);
    }

    public function yoast_missing_notice(): void {
        if (!$this->yoast_active && current_user_can('manage_options')) {
            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p><strong>Exclude Post Types from Sitemap:</strong> This module requires the <em>Yoast SEO</em> plugin to be active.</p>';
            echo '</div>';
        }
    }
}

new SitemapExcludePostTypesModule();
