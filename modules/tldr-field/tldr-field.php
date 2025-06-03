<?php

defined('ABSPATH') || exit;

class MP_TLDR_Field {
    public function __construct() {
        add_action('add_meta_boxes', [$this, 'register_meta_box']);
        add_action('save_post', [$this, 'save_meta']);
        add_filter('the_content', [$this, 'inject_tldr']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);

    }

    public function register_meta_box(): void
    {
        $post_types = get_option('marketing_planet_tldr_post_types', ['post']);
        foreach ($post_types as $type) {
            add_meta_box('tldr_summary', 'TL;DR Summary', [$this, 'render_meta_box'], $type, 'normal', 'high');
        }
    }

    public function render_meta_box($post): void
    {
        $value = get_post_meta($post->ID, '_marketing_planet_tldr', true);
        wp_editor($value, 'marketing_planet_tldr', [
            'textarea_name' => 'marketing_planet_tldr',
            'media_buttons' => false,
            'textarea_rows' => 5,
        ]);
    }

    public function save_meta($post_id): void
    {
        if (isset($_POST['marketing_planet_tldr'])) {
            update_post_meta($post_id, '_marketing_planet_tldr', wp_kses_post($_POST['marketing_planet_tldr']));
        }
    }

    public function inject_tldr($content): mixed
    {
        if (!is_singular() || !in_the_loop() || !is_main_query()) return $content;

        $tldr = get_post_meta(get_the_ID(), '_marketing_planet_tldr', true);
        if (empty($tldr)) return $content;

        if (preg_match('/<h2.*?>/', $content, $matches, PREG_OFFSET_CAPTURE)) {
            $pos = $matches[0][1];
            return substr($content, 0, $pos) . '<div class="tldr-summary">' . wpautop($tldr) . '</div>' . substr($content, $pos);
        }

        return $content . '<div class="tldr-summary">' . wpautop($tldr) . '</div>';
    }

    public function enqueue_styles(): void
    {
        $upload_dir = wp_upload_dir();
        $dynamic_path = trailingslashit($upload_dir['baseurl']) . 'marketing-planet/tldr-field/tldr-field.css';
        $dynamic_file = trailingslashit($upload_dir['basedir']) . 'marketing-planet/tldr-field/tldr-field.css';

        // Enqueue base styles (from plugin folder)
        wp_enqueue_style(
            'mp-tldr-base',
            plugin_dir_url(__FILE__) . 'tldr-field-base.css',
            [],
            filemtime(plugin_dir_path(__FILE__) . 'tldr-field-base.css')
        );

        // Enqueue dynamic vars (from uploads)
        if (file_exists($dynamic_file)) {
            wp_enqueue_style(
                'mp-tldr-vars',
                $dynamic_path,
                ['mp-tldr-base'],
                filemtime($dynamic_file)
            );
        }
    }


}

// Instantiate the class
new MP_TLDR_Field();
