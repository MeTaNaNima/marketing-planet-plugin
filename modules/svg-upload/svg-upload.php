<?php
// Module: SVG Upload Support (OOP)

defined('ABSPATH') || exit;
/** @var TYPE_NAME $GLOBALS */
$GLOBALS['marketing_planet_module_titles']['svg-upload'] = 'Allow SVG Uploads';

class MP_SVG_Upload {
    public function __construct() {
        add_filter('upload_mimes', [$this, 'allow_svg_mime']);
        add_filter('wp_check_filetype_and_ext', [$this, 'fix_svg_filetype'], 10, 4);
    }

    /**
     * Allow SVG uploads for admins.
     */
    public function allow_svg_mime($mimes) {
        if (current_user_can('manage_options')) {
            $mimes['svg'] = 'image/svg+xml';
        }
        return $mimes;
    }

    /**
     * Fix SVG preview and validation in Media Library.
     */
    public function fix_svg_filetype($data, $file, $filename, $mimes): array
    {
        $filetype = wp_check_filetype($filename, $mimes);
        return [
            'ext'             => $filetype['ext'],
            'type'            => $filetype['type'],
            'proper_filename' => $data['proper_filename'],
        ];
    }
}

// Instantiate the class
new MP_SVG_Upload();
