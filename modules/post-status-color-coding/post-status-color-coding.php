<?php

class PostStatusColorCoding {

    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
    }

    public function enqueue_admin_styles(): void
    {
        $screen = get_current_screen();
        if (is_admin() && $screen && $screen->base === 'edit') {
            add_action('admin_head', array($this, 'output_custom_styles'));
        }
    }

    public function output_custom_styles(): void
    {
        echo '<style>
            /* Background Color based on Post Status */
            .status-draft {
                background-color: #ffe8ab40 !important;
            }
            .status-draft span.post-state {
                color: #f08080;
            }
            .status-private {
                background-color: #cdd3ff !important;
            }
        </style>';
    }
}

new PostStatusColorCoding();
