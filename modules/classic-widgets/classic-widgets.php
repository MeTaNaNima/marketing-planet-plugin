<?php
// Module: Classic Widgets (OOP)

defined('ABSPATH') || exit;
/** @var TYPE_NAME $GLOBALS */
$GLOBALS['marketing_planet_module_titles']['classic-widgets'] = 'Enable Classic Widgets';
class MP_Classic_Widgets {
    public function __construct() {
        add_filter('gutenberg_use_widgets_block_editor', '__return_false');
        add_filter('use_widgets_block_editor', '__return_false');
    }
}

// Instantiate the class
new MP_Classic_Widgets();
