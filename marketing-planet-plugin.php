<?php

/**
 * Plugin Name: Marketing Planet Plugin
 * Plugin URI: https://marketingplanet.agency/
 * Description: A modular plugin developed by Marketing Planet for reusable, high-performance functionality across multiple sites.
 * Version: 0.1.1
 * Author: Marketing Planet
 * Author URI: https://marketingplanet.agency/
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: marketing-planet-plugin
 */

// TODO: Schema (Dynamic + Auto + Custom)
// TODO: Google Reviews

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
    // Only load in admin area
    if (!is_admin()) return;

    wp_enqueue_style(
        'mp-admin-css',
        plugins_url('assets/css/admin.css', __FILE__),  // Path to CSS file
        [],  // No dependencies
        filemtime(plugin_dir_path(__FILE__) . 'assets/css/admin.css')  // Versioning based on file modification time
    );
});


add_action('wp_enqueue_scripts', function () {
    $upload_dir = wp_upload_dir();
    $custom_css_path = trailingslashit($upload_dir['basedir']) . 'marketing-planet/mp-front.css';
    $custom_css_url  = trailingslashit($upload_dir['baseurl']) . 'marketing-planet/mp-front.css';

    if (file_exists($custom_css_path)) {
        wp_enqueue_style('marketing-planet-custom-css', $custom_css_url, [], filemtime($custom_css_path));
    }
});



add_action('admin_init', function () {
    // Verify nonce for security
    if (isset($_POST['marketing_planet_nonce']) && !empty($_POST['marketing_planet_nonce'])) {
        if (!check_admin_referer('marketing_planet_settings', 'marketing_planet_nonce')) {
            // If nonce is invalid, stop the process and show an error
            wp_die('Invalid request. Nonce verification failed.');
        }
    }

    // If nonce is valid, process your form and add a success message
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Example of your form processing logic
        // You can add your settings saving here

        // Add success message
        add_settings_error(
            'marketing_planet_settings_group', // Settings group
            'marketing_planet_success', // Message ID
            'Settings have been saved successfully!', // Message text
            'updated' // Message type ('updated' class for success)
        );

        // Optionally, redirect to avoid form resubmission on page reload
        // wp_redirect(admin_url('admin.php?page=marketing-planet-settings'));
        // exit;
    }
});
