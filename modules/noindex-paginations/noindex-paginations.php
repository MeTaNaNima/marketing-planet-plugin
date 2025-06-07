<?php
// modules/class-noindex-paginations.php

$GLOBALS['marketing_planet_module_titles']['noindex-paginations'] = 'No-index Paginations (Yoast)';

class NoIndexPaginationsModule {
    private $yoast_active;

    public function __construct() {
//        if (is_plugin_active('wordpress-seo/wp-seo.php') || is_plugin_active('wordpress-seo-premium/wp-seo-premium.php') ) {
//            $this->yoast_active = true;
//        }

        $this->yoast_active = $this->is_yoast_active();

        add_action('admin_init', [$this, 'maybe_disable_module_setting']);
        add_action('admin_notices', [$this, 'yoast_missing_notice']);

        // Delay logic until after plugins are loaded
        add_action('plugins_loaded', [$this, 'maybe_enable_feature'], 999);
    }

    private function is_yoast_active(): bool {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        return is_plugin_active('wordpress-seo/wp-seo.php') || is_plugin_active('wordpress-seo-premium/wp-seo-premium.php');
    }


    public function maybe_enable_feature(): void
    {
        if (!$this->yoast_active || !$this->is_module_enabled()) {
            return;
        }

        add_filter('wpseo_robots', [$this, 'filter_yoast_robots'], 999);
    }

    public function filter_yoast_robots($robots) {
        if (is_paged()) {
            error_log('Yoast pagination noindex triggered'); // ✅ Add this line

            return 'noindex,follow';
        }
        return $robots;
    }

    private function is_module_enabled(): bool
    {
        $active = get_option('marketing_planet_active_modules', []);
        return in_array('noindex-paginations', $active, true);
    }


    public function maybe_disable_module_setting(): void
    {
        if (!$this->yoast_active) {
            add_filter('pre_option_noindex_paginations_module_enabled', '__return_empty_string');
        }
    }

    public function yoast_missing_notice(): void
    {
        if (!$this->yoast_active && current_user_can('manage_options')) {
            echo '<div class="notice notice-warning is-dismissible">';
            echo '<p><strong>No-index Paginations:</strong> This module requires the <em>Yoast SEO</em> plugin to be active.</p>';
            echo '</div>';
        }
    }
}

new NoIndexPaginationsModule();
