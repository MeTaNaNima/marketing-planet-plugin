<?php
// Module: Classic Editor (OOP)

defined('ABSPATH') || exit;

class MP_Classic_Editor {
    public function __construct() {
        add_filter('use_block_editor_for_post', '__return_false', 10);
        add_filter('use_block_editor_for_post_type', '__return_false', 10);

        add_action('wp_enqueue_scripts', [$this, 'remove_block_styles'], 100);
        add_action('admin_enqueue_scripts', [$this, 'remove_admin_block_assets'], 100);
    }

    /**
     * Remove Gutenberg/block styles from frontend.
     */
    public function remove_block_styles(): void
    {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
        wp_dequeue_style('global-styles');
    }

    /**
     * Remove block-related scripts and styles from admin.
     */
    public function remove_admin_block_assets(): void
    {
        wp_dequeue_script('wp-blocks');
        wp_dequeue_script('wp-editor');
        wp_dequeue_style('wp-block-library');
    }
}

// Instantiate the class
new MP_Classic_Editor();
