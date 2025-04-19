<?php
class Advanced_Plugin_Post_Type
{
    public static function register()
    {
        register_post_type('custom_post', [
            'labels' => [
                'name' => 'Custom Posts',
                'singular_name' => 'Custom Post',
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'custom-fields'],
            'menu_icon' => 'dashicons-admin-post',
        ]);
    }

    public function __construct()
    {
        add_action('init', [__CLASS__, 'register']);
        add_action('save_post', [$this, 'save_post'], 10, 3);
    }

    public function save_post($post_id, $post, $update)
    {
        if ($post->post_type !== 'custom_post' || !current_user_can('edit_post', $post_id)) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        $database = new Advanced_Plugin_Database();
        $new_meta_value = isset($_POST["custom-meta-value"]) ? sanitize_text_field($_POST["custom-meta-value"]) : '';

        $database->save_data($post_id, $new_meta_value, $update);

        if (!empty($new_meta_value)) {
            update_post_meta($post->ID, 'custom_meta_key', $new_meta_value);
        } else {
            delete_post_meta($post->ID, 'custom_meta_key');
        }
    }
}
