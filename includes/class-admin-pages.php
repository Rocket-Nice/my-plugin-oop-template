<?php
class Advanced_Plugin_Admin_Pages
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_admin_pages']);
    }

    public function add_admin_pages()
    {
        add_menu_page(
            'Advanced Plugin',
            'Advanced Plugin',
            'manage_options',
            'advanced-plugin-main',
            [$this, 'render_main_page'],
            'dashicons-admin-generic'
        );

        add_submenu_page(
            'advanced-plugin-main',
            'Настройки',
            'Настройки',
            'manage_options',
            'advanced-plugin-settings',
            [$this, 'render_settings_page']
        );
    }

    public function render_main_page()
    {
        $database = new Advanced_Plugin_Database();
        $results = $database->get_all_data();
?>
        <div class="wrap">
            <h1>Управление Custom Posts</h1>
            <p>Выберите действие для управления записями:</p>
            <form id="custom-posts-form">
                <label for="custom-post-action">Действие:</label>
                <select id="custom-post-action" name="action">
                    <option value="delete">Удалить все записи</option>
                    <option value="update">Обновить мета-данные</option>
                </select>
                <button type="button" id="custom-posts-submit">Применить</button>
            </form>
            <div id="custom-posts-response"></div>
            <h2>Данные из таблицы</h2>
            <table class="widefat">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ID записи</th>
                        <th>Дополнительные данные</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <td><?php echo esc_html($row->id); ?></td>
                            <td><?php echo esc_html($row->post_id); ?></td>
                            <td><?php echo esc_html($row->additional_data); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php
    }

    public function render_settings_page()
    {
    ?>
        <div class="wrap">
            <h1>Настройки Advanced Plugin</h1>
            <form id="plugin-settings-form">
                <label for="custom-option">Ключ API:</label>
                <input type="text" id="custom-option" name="custom_option" value="<?php echo esc_attr(get_option('advanced_plugin_option', '')); ?>">
                <button type="button" id="save-settings">Сохранить</button>
            </form>
            <div id="settings-response"></div>
        </div>
<?php
    }
}
