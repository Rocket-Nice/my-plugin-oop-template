<?php
class Advanced_Plugin_Ajax_Handler
{
    public function __construct()
    {
        add_action('wp_ajax_handle_posts_action', [$this, 'handle_posts_action']);
        add_action('wp_ajax_save_plugin_settings', [$this, 'save_settings']);
    }

    private function verify_request()
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Недостаточно прав']);
        }

        if (!isset($_POST['_ajax_nonce']) || !wp_verify_nonce($_POST['_ajax_nonce'], 'advanced_plugin_nonce')) {
            wp_send_json_error(['message' => 'Ошибка безопасности']);
        }
    }

    public function handle_posts_action()
    {
        $this->verify_request();

        global $wpdb;
        $action = sanitize_text_field($_POST['action_type']);
        $database = new Advanced_Plugin_Database();

        $response = '';
        if ($action === 'delete') {
            $posts = get_posts(['post_type' => 'custom_post', 'numberposts' => -1, 'fields' => 'ids']);
            foreach ($posts as $post_id) {
                wp_delete_post($post_id, true);
                $database->delete_data($post_id);
            }
            $response = 'Все записи удалены.';
        } elseif ($action === 'update') {
            $posts = get_posts(['post_type' => 'custom_post', 'numberposts' => -1]);
            $new_meta_value = 'Обновлено: ' . current_time('mysql');

            foreach ($posts as $post) {
                update_post_meta($post->ID, 'custom_meta_key', $new_meta_value);
                $database->update_data($post->ID, $new_meta_value);
            }
            $response = 'Мета-данные и данные таблицы обновлены.';
        }

        wp_send_json_success(['message' => $response]);
    }

    public function save_settings()
    {
        $this->verify_request();

        if (isset($_POST['custom_option'])) {
            update_option(
                'advanced_plugin_option',
                sanitize_text_field($_POST['custom_option'])
            );
            wp_send_json_success(['message' => 'Настройки сохранены.']);
        }

        wp_send_json_error(['message' => 'Некорректные данные.']);
    }
}
