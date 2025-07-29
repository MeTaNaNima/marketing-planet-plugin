<?php
namespace MP\Modules\FaqRepeater;


/** @var TYPE_NAME $GLOBALS */
$GLOBALS['marketing_planet_module_titles']['faq-repeater'] = 'FAQ Repeater';

defined('ABSPATH') || exit;


class FaqRepeaterModule {
    const OPTION_KEY = 'marketing_planet_faq_post_types';
    private bool $faq_shortcode_used = false;

    /**
     * Constructor
     */
    public function __construct() {
        add_action('add_meta_boxes', [$this, 'register_metabox']);
        add_action('save_post', [$this, 'save_faq_data'], 10, 2); // Ensure we have the post object
        add_action('wp_footer', [$this, 'render_schema'], 99);
        add_shortcode('mp_faqs', [$this, 'render_shortcode']);
        add_filter('the_content', [$this, 'append_faq_to_content']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Register Metabox
     */
    public function register_metabox(): void
    {
        $enabled_post_types = get_option(self::OPTION_KEY, []);
        if (is_array($enabled_post_types)) {
            foreach ($enabled_post_types as $type) {
                add_meta_box('faq_repeater_box', 'FAQs', [$this, 'render_metabox'], $type, 'normal', 'default');
            }
        } else {
            error_log('The FAQ post types option is not an array.');
        }
    }

    /**
     * Render Metabox
     */
    public function render_metabox($post): void
    {
        $faqs = get_post_meta($post->ID, '_faq_repeater_data', true) ?: [];
        include __DIR__ . '/view-metabox.php';
    }

    /**
     * Save FAQ Data
     */
    public function save_faq_data($post_id, $post): void
    {
        // Skip if autosave, revision, or post type is not enabled
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (wp_is_post_revision($post_id)) return;

        $post_type = get_post_type($post_id);
        $enabled_post_types = get_option(self::OPTION_KEY, []);
        if (!in_array($post_type, $enabled_post_types)) return;

        // Check if the post is being saved in the native WordPress editor (Classic or Gutenberg)
        if (!$this->is_wp_editor_save($post)) {
            return; // Skip saving FAQ data if it's not from the native WP editor
        }

        // Process FAQ data
        if (!empty($_POST['faq_repeater'])) {
            $cleaned = array_values(array_filter($_POST['faq_repeater'], function($item) {
                return !empty($item['question']) || !empty($item['answer']);
            }));

            // Only update if there's valid FAQ data
            if (!empty($cleaned)) {
                update_post_meta($post_id, '_faq_repeater_data', wp_kses_post_deep($cleaned));
            } else {
                delete_post_meta($post_id, '_faq_repeater_data'); // Remove if FAQ is empty
            }
        } else {
            delete_post_meta($post_id, '_faq_repeater_data'); // No FAQ data to save, so delete
        }
    }

    /**
     * Check if the post is being saved in the WP editor (Classic or Gutenberg)
     */
    private function is_wp_editor_save($post): bool
    {
        // Check if it's a normal save (WordPress editor - either Gutenberg or Classic)
        if (isset($_POST['action']) && $_POST['action'] === 'editpost') {
            return true;
        }

        // It's not from the native WP editor, so return false
        return false;
    }

    /**
     * Render FAQ schema
     */
    public function render_schema(): void
    {
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

    /**
     * Render Shortcode for FAQ
     */
    public function render_shortcode($atts): string {
        $this->faq_shortcode_used = true;
        if (!is_singular()) return '';

        $post_id = get_the_ID();
        $faqs = get_post_meta($post_id, '_faq_repeater_data', true);
        if (!$faqs || !is_array($faqs)) return '';

        // Output accordion HTML
        ob_start();
        ?>
        <div class="mp-faq-wrapper">
            <?php foreach ($faqs as $index => $item): ?>
                <div class="mp-faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <button class="mp-faq-question" itemprop="name"><?= esc_html($item['question']) ?></button>
                    <div class="mp-faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <div itemprop="text"><?= wpautop(wp_kses_post($item['answer'])) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Enqueue Scripts and Styles
     */
    public function enqueue_assets(): void
    {
        // Get the list of enabled post types for the FAQ module
        $enabled_post_types = get_option(self::OPTION_KEY, []);

        // Check if the current post type is in the list of enabled post types
        if (!is_singular() || !in_array(get_post_type(), $enabled_post_types)) {
            return; // Don't enqueue assets if not in enabled post types
        }

        // $should_enqueue = $this->faq_shortcode_used || get_option('marketing_planet_faq_auto_append');

        // if (!$should_enqueue) return;

        $base_url = plugin_dir_url(__FILE__);
        $base_path = plugin_dir_path(__FILE__);

        wp_enqueue_style(
            'mp-faq-style',
            $base_url . 'faq-repeater-base.css',
            [],
            filemtime($base_path . 'faq-repeater-base.css')
        );

        wp_enqueue_script(
            'mp-faq-script',
            $base_url . 'faq-repeater.js',
            [],
            filemtime($base_path . 'faq-repeater.js'),
            true
        );
    }

    /**
     * Append FAQ to Post Content
     */
    public function append_faq_to_content($content): mixed
    {
        if (!is_singular() || !in_the_loop() || !is_main_query()) return $content;

        // Only append if setting is enabled
        $append_enabled = get_option('marketing_planet_faq_auto_append');
        if (!$append_enabled) return $content;

        $faq_html = $this->render_shortcode([]);
        return $content . $faq_html;
    }
}