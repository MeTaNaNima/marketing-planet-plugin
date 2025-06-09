<?php

/**
 * Plugin Name: Marketing Planet Plugin
 * Plugin URI: https://marketingplanet.agency/
 * Description: A modular plugin developed by Marketing Planet for reusable, high-performance functionality across multiple sites.
 * Version: 0.0.7
 * Author: Marketing Planet
 * Author URI: https://marketingplanet.agency/
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: marketing-planet-plugin
 */

defined('ABSPATH') || exit;

// Auto Update:
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/update-checker.php';
(new \MP\UpdateChecker(
    __FILE__,
    'https://github.com/MeTaNaNima/marketing-planet-plugin',
    'marketing-planet-plugin'
))->init();


// Include core includes
require_once plugin_dir_path(__FILE__) . 'includes/init.php';


// Auto-load only activated modules
$active_modules = get_option('marketing_planet_active_modules', []);
if (!is_array($active_modules)) {
    $active_modules = [];
}
$modules_dir = plugin_dir_path(__FILE__) . 'modules/';

foreach ($active_modules as $module) {
    $module_file = $modules_dir . $module . '/' . $module . '.php';
    if (file_exists($module_file)) {
        require_once $module_file;
    }
}

if (in_array('faq-repeater', $active_modules)) {
    new \MP\Modules\FaqRepeater\FaqRepeaterModule();
}


add_action('admin_enqueue_scripts', function () {
    // Only enqueue on the plugin's settings page
    $screen = get_current_screen();
    if (strpos($screen->id, 'marketing-planet-settings') !== false) {
        wp_enqueue_style(
            'mp-admin-css',
            plugins_url('assets/css/admin.css', __FILE__),
            [],
            filemtime(plugin_dir_path(__FILE__) . 'assets/css/admin.css')
        );
    }
});

add_action('wp_enqueue_scripts', function () {
    $upload_dir = wp_upload_dir();
    $custom_css_path = trailingslashit($upload_dir['basedir']) . 'marketing-planet/mp-front.css';
    $custom_css_url  = trailingslashit($upload_dir['baseurl']) . 'marketing-planet/mp-front.css';

    if (file_exists($custom_css_path)) {
        wp_enqueue_style('marketing-planet-custom-css', $custom_css_url, [], filemtime($custom_css_path));
    }
});

