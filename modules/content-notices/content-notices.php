<?php
// Module: Content Notices (OOP)
/** @var TYPE_NAME $GLOBALS */
$GLOBALS['marketing_planet_module_titles']['content-notices'] = 'Content Notices Shortcode (Classic Editor Only)';

defined('ABSPATH') || exit;

class MP_Content_Notices {
    public function __construct() {
        add_shortcode('notice', [$this, 'render_notice_shortcode']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        add_action('admin_init', [$this, 'register_mce_button']);
    }

    /**
     * Render the [notice type="{TYPES: INFO, SUCCESS, WARNING, DANGER}"] shortcode.
     */
    public function render_notice_shortcode($atts, $content = ''): string
    {
        $atts = shortcode_atts([
            'type' => 'info',
        ], $atts, 'notice');

        $allowed = ['info', 'success', 'warning', 'danger'];
        $type = in_array($atts['type'], $allowed) ? $atts['type'] : 'info';

        return '<div class="mp-notice mp-notice-' . esc_attr($type) . '">' . do_shortcode($content) . '</div>';
    }

    /**
     * Enqueue inline CSS styles for notice types.
     */
    public function enqueue_styles(): void
    {
        $upload_dir = wp_upload_dir();

        // Enqueue base styles (from plugin folder)
        wp_enqueue_style(
            'mp-content-notices-base',
            plugin_dir_url(__FILE__) . 'content-notices-base.css',
            [],
            filemtime(plugin_dir_path(__FILE__) . 'content-notices-base.css')
        );
    }

    /**
     * Register TinyMCE button to insert notice shortcodes.
     */
    public function register_mce_button(): void
    {
        add_filter('mce_external_plugins', function ($plugins) {
            $plugins['mp_notice_button'] = plugins_url('notice-button.js', __FILE__);
            return $plugins;
        });

        add_filter('mce_buttons', function ($buttons) {
            $buttons[] = 'mp_notice_button';
            return $buttons;
        });
    }
}

// Instantiate the class
new MP_Content_Notices();
