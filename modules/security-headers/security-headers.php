<?php
class MP_Security_Headers {
    const OPTION_KEY = 'mp_security_headers_method'; // You may keep this if you want to track the method or simply hard-code it to PHP
    const HTACCESS_WRITTEN_OPTION = 'mp_htaccess_written';  // Option flag can be omitted if not using .htaccess

    public function __construct() {
        add_action('admin_menu', [$this, 'register_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('init', [$this, 'apply_headers']);
        // add_action('update_option_marketing_planet_security_headers_method', [$this, 'handle_method_change'], 10, 2);
        // add_action('update_option_marketing_planet_active_modules', [$this, 'handle_module_toggle'], 10, 2);
    }

    public function register_settings_page(): void {
        add_options_page(
            'Security Headers',
            'Security Headers',
            'manage_options',
            'mp-security-headers',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings(): void {
        // Register the setting, but we no longer need to store the method in options.
        register_setting('mp_security_headers', self::OPTION_KEY);
        add_settings_section('default', '', null, 'mp-security-headers');
        add_settings_field(
            self::OPTION_KEY,
            'Header Injection Method',
            [$this, 'render_method_field'],
            'mp-security-headers',
            'default'
        );
    }

    public function render_settings_page(): void {
        ?>
        <div class="wrap">
            <h1>Security Headers Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('mp_security_headers');
                do_settings_sections('mp-security-headers');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function apply_headers(): void {
        error_log("MP_Security_Headers: Applying PHP headers method.");
        $this->add_php_headers();
    }

    private function add_php_headers(): void {
        error_log("MP_Security_Headers: Adding PHP headers.");

        add_action('send_headers', function () {
            header("Access-Control-Allow-Methods: GET,POST");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
            header("Content-Security-Policy: upgrade-insecure-requests;");
            header("Cross-Origin-Embedder-Policy: unsafe-none; report-to='default'");
            header("Cross-Origin-Embedder-Policy-Report-Only: unsafe-none; report-to='default'");
            header("Cross-Origin-Opener-Policy: unsafe-none");
            header("Cross-Origin-Opener-Policy-Report-Only: unsafe-none; report-to='default'");
            header("Cross-Origin-Resource-Policy: cross-origin");
            header("Permissions-Policy: accelerometer=(), autoplay=(), camera=(), cross-origin-isolated=(), display-capture=(self), encrypted-media=(), fullscreen=*, geolocation=(self), gyroscope=(), keyboard-map=(), magnetometer=(), microphone=(), midi=(), payment=*, picture-in-picture=*, publickey-credentials-get=(), screen-wake-lock=(), sync-xhr=*, usb=(), xr-spatial-tracking=(), gamepad=(), serial=()");
            header("Referrer-Policy: strict-origin-when-cross-origin");
            header("Strict-Transport-Security: max-age=63072000");
            header("X-Content-Security-Policy: default-src 'self'; img-src *; media-src * data:;");
            header("X-Content-Type-Options: nosniff");
            header("X-Frame-Options: SAMEORIGIN");
            header("X-Permitted-Cross-Domain-Policies: none");
        }, 0);
    }

    // private function write_htaccess_headers() {
    //     error_log("MP_Security_Headers: Skipping .htaccess writing, as the method is set to PHP.");
    //     return;
    // }

    // public function handle_method_change($old_value, $new_value): void {
    //     error_log("MP_Security_Headers: Method changed from {$old_value} to {$new_value}.");
    //     // Block any changes to PHP method. You can update this logic to make sure only PHP remains.
    //     if ($old_value === 'htaccess' && $new_value === 'php') {
    //         error_log("MP_Security_Headers: Cannot change from htaccess to php. Keeping PHP method.");
    //         update_option(self::OPTION_KEY, 'php');
    //     }
    // }

    // public function handle_module_toggle($old, $new): void {
    //     $was_active = in_array('security-headers', (array) $old);
    //     $is_active = in_array('security-headers', (array) $new);
    //
    //     if ($was_active && !$is_active) {
    //         error_log("MP_Security_Headers: Deactivating security headers.");
    //     }
    // }
}

new MP_Security_Headers();
