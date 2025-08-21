<?php

if (!defined('ABSPATH')) exit;

class MP_Date_Module {
    public function __construct() {
        add_shortcode('mp_date', [$this, 'render_date_shortcode']);
    }

    /**
     * Handles the [mp_date] shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string Localized and formatted date string.
     * @throws DateInvalidTimeZoneException
     */
    public function render_date_shortcode($atts): string
    {
        $atts = shortcode_atts([
            'format' => 'Y-m-d',   // Default format
            'timezone' => '',      // Optional: override site timezone
        ], $atts, 'mp_date');

        // Sanitize format: Allow only valid characters (no user-provided PHP code)
        $format = preg_replace('/[^A-Za-z\-\/:\s,.]/', '', $atts['format']);

        // Use provided timezone if valid, otherwise fallback to WordPress default
        $tz = $atts['timezone'];
        if ($tz && in_array($tz, timezone_identifiers_list())) {
            $datetime = new DateTime('now', new DateTimeZone($tz));
        } else {
            $datetime = new DateTime('now', wp_timezone());
        }

        // Return localized date format
        return date_i18n($format, $datetime->getTimestamp());
    }
}

// Initialize the module
new MP_Date_Module();
