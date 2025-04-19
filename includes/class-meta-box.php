<?php
class Advanced_Plugin_Meta_Box
{
    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'add_meta_box']);
    }

    public function add_meta_box()
    {
        add_meta_box(
            'custom_meta_box',
            'Custom Meta Box',
            [$this, 'render_meta_box'],
            'custom_post',
            'side',
            'default'
        );
    }

    public function render_meta_box($post)
    {
        $meta_value = get_post_meta($post->ID, 'custom_meta_key', true);
?>
        <p><strong>Мета-данные:</strong></p>
        <p>
            <label for="custom-meta-value"><?php _e('Содержимое:', 'textdomain'); ?></label>
            <br>
            <input id="custom-meta-value" name="custom-meta-value" style="width: 100%;" value="<?php echo esc_attr($meta_value ? $meta_value : 'Нет данных'); ?>">
        </p>
<?php
    }
}
