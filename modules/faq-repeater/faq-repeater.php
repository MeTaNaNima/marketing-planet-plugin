<?php
namespace MP\Modules\FaqRepeater;

defined('ABSPATH') || exit;

class FaqRepeaterModule {
    const OPTION_KEY = 'marketing_planet_faq_post_types';

    public function __construct() {
        add_action('add_meta_boxes', [$this, 'register_metabox']);
        add_action('save_post', [$this, 'save_faq_data']);
        add_action('wp_footer', [$this, 'render_schema'], 99);
    }

    public function register_metabox() {
        $enabled_post_types = get_option(self::OPTION_KEY, []);
        foreach ($enabled_post_types as $type) {
            add_meta_box('faq_repeater_box', 'FAQs', [$this, 'render_metabox'], $type, 'normal', 'default');
        }
    }

    public function render_metabox($post) {
        $faqs = get_post_meta($post->ID, '_faq_repeater_data', true) ?: [];
        include __DIR__ . '/view-metabox.php';
    }

    public function save_faq_data($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        if (!empty($_POST['faq_repeater'])) {
            $cleaned = array_values(array_filter($_POST['faq_repeater'], function($item) {
                return !empty($item['question']) || !empty($item['answer']);
            }));
            update_post_meta($post_id, '_faq_repeater_data', wp_kses_post_deep($cleaned));
        } else {
            delete_post_meta($post_id, '_faq_repeater_data');
        }
    }

    public function render_schema() {
        if (!is_singular()) return;
        $post_id = get_the_ID();
        $faqs = get_post_meta($post_id, '_faq_repeater_data', true);

        if (!$faqs || !is_array($faqs)) return;

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(function($item) {
                return [
                    '@type' => 'Question',
                    'name' => wp_strip_all_tags($item['question']),
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => wp_kses_post($item['answer']),
                    ]
                ];
            }, $faqs)
        ];

        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }
}
