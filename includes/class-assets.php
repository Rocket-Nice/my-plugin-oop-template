<?php
class Advanced_Plugin_Assets
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    public function enqueue_assets($hook)
    {
        if (strpos($hook, 'advanced-plugin') !== false) {
            $this->enqueue_scripts();
            $this->enqueue_styles();
            $this->localize_scripts();
            $this->add_inline_styles();
        }
    }

    private function enqueue_scripts()
    {
        wp_enqueue_script(
            'advanced-plugin-scripts',
            ADVANCED_PLUGIN_URL . 'assets/js/admin-scripts.js',
            [],
            ADVANCED_PLUGIN_VERSION,
            true
        );
    }

    private function enqueue_styles()
    {
        wp_enqueue_style(
            'advanced-plugin-css',
            ADVANCED_PLUGIN_URL . 'assets/css/style.css',
            [],
            ADVANCED_PLUGIN_VERSION
        );
    }

    private function localize_scripts()
    {
        wp_localize_script('advanced-plugin-scripts', 'pluginData', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('advanced_plugin_nonce'),
        ]);
    }

    private function add_inline_styles()
    {
        $custom_color = get_option('advanced_plugin_option', '#1d2327');

        if (
            !preg_match('/^#[a-fA-F0-9]{6}$/', $custom_color) &&
            !preg_match('/^[a-zA-Z]+$/', $custom_color)
        ) {
            $custom_color = '#1d2327';
        }

        $inline_style = ":root { --custom-background-color: {$custom_color}; }";
        wp_add_inline_style('advanced-plugin-css', $inline_style);
    }
}
