<?php

/**
 * Plugin Name: Marketing Planet Plugin
 * Plugin URI: https://marketingplanet.agency/
 * Description: A modular plugin developed by Marketing Planet for reusable, high-performance functionality across multiple sites.
 * Version: 0.0.4
 * Author: Marketing Planet
 * Author URI: https://marketingplanet.agency/
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: marketing-planet-plugin
 */

defined('ABSPATH') || exit;

// Auto Update:
require_once __DIR__ . '/vendor/autoload.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$updateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/yourusername/your-repo-name/',
    __FILE__,
    'your-plugin-slug'
);

// Optional: For private repo authentication
$updateChecker->setAuthentication('your-github-token');

// Optional: Set the branch
$updateChecker->getVcsApi()->setBranch('main');  // or 'master'


// Include core includes
require_once plugin_dir_path(__FILE__) . 'includes/init.php';


// Auto-load only activated modules
$active_modules = get_option('marketing_planet_active_modules', []);
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

