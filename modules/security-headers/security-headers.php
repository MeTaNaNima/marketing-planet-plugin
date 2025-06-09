<?php

class MP_Security_Headers {
    const OPTION_KEY = 'mp_security_headers_method';

    public function __construct() {
        add_action('admin_menu', [$this, 'register_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('init', [$this, 'apply_headers']);
        add_action('update_option_marketing_planet_security_headers_method', [$this, 'handle_method_change'], 10, 2);
        add_action('update_option_marketing_planet_active_modules', [$this, 'handle_module_toggle'], 10, 2);
    }

    public function register_settings_page(): void
    {
        add_options_page(
            'Security Headers',
            'Security Headers',
            'manage_options',
            'mp-security-headers',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings(): void
    {
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

    public function render_settings_page(): void
    {
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

    public function render_method_field(): void
    {
        $value = get_option(self::OPTION_KEY, 'htaccess');
        ?>
        <select name="<?php echo esc_attr(self::OPTION_KEY); ?>">
            <option value="htaccess" <?php selected($value, 'htaccess'); ?>>.htaccess (Default)</option>
            <option value="php" <?php selected($value, 'php'); ?>>PHP Fallback</option>
        </select>
        <?php
    }

    public function apply_headers(): void
    {
        $method = get_option(self::OPTION_KEY, 'htaccess');
        if ($method === 'php') {
            $this->add_php_headers();
        } else {
            $this->write_htaccess_headers();
        }
    }

    private function add_php_headers(): void
    {
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

    private function write_htaccess_headers() {
        $htaccess_path = ABSPATH . '.htaccess';
        if (!is_writable($htaccess_path)) {
            return;
        }

        $marker_start = "# BEGIN MP Resolving Security Issues";
        $marker_end = "# END MP Resolving Security Issues";

        $headers_block = <<<HTA
{$marker_start}
<IfModule mod_headers.c>
Header set Access-Control-Allow-Methods "GET,POST"
Header set Access-Control-Allow-Headers "Content-Type, Authorization"
Header set Content-Security-Policy "upgrade-insecure-requests;"
Header set Cross-Origin-Embedder-Policy "unsafe-none; report-to='default'"
Header set Cross-Origin-Embedder-Policy-Report-Only "unsafe-none; report-to='default'"
Header set Cross-Origin-Opener-Policy "unsafe-none"
Header set Cross-Origin-Opener-Policy-Report-Only "unsafe-none; report-to='default'"
Header set Cross-Origin-Resource-Policy "cross-origin"
Header set Permissions-Policy "accelerometer=(), autoplay=(), camera=(), cross-origin-isolated=(), display-capture=(self), encrypted-media=(), fullscreen=*, geolocation=(self), gyroscope=(), keyboard-map=(), magnetometer=(), microphone=(), midi=(), payment=*, picture-in-picture=*, publickey-credentials-get=(), screen-wake-lock=(), sync-xhr=*, usb=(), xr-spatial-tracking=(), gamepad=(), serial=()"
Header set Referrer-Policy "strict-origin-when-cross-origin"
Header set Strict-Transport-Security "max-age=63072000"
Header set X-Content-Security-Policy "default-src 'self'; img-src *; media-src * data:;"
Header set X-Content-Type-Options "nosniff"
Header set X-Frame-Options "SAMEORIGIN"
Header set X-Permitted-Cross-Domain-Policies "none"
</IfModule>
{$marker_end}
HTA;

        $original = file_get_contents($htaccess_path);
        $updated = preg_replace("/{$marker_start}.*?{$marker_end}/s", '', $original); // remove old block
        $updated = trim($updated) . "\n\n" . $headers_block;

        file_put_contents($htaccess_path, $updated);
    }

    public function handle_method_change($old_value, $new_value): void
    {
        if ($old_value === 'htaccess' && $new_value === 'php') {
            $this->remove_htaccess_block();
        }
    }

    public function handle_module_toggle($old, $new): void
    {
        $was_active = in_array('security-headers', (array) $old);
        $is_active = in_array('security-headers', (array) $new);

        if ($was_active && !$is_active) {
            $this->remove_htaccess_block();
        }
    }

    private function remove_htaccess_block() {
        $htaccess_path = ABSPATH . '.htaccess';
        if (!is_writable($htaccess_path) || !file_exists($htaccess_path)) {
            return;
        }

        $content = file_get_contents($htaccess_path);
        $pattern = "/# BEGIN MP Resolving Security Issues.*?# END MP Resolving Security Issues/s";
        $new_content = preg_replace($pattern, '', $content);
        file_put_contents($htaccess_path, trim($new_content) . "\n");
    }



}

new MP_Security_Headers();
