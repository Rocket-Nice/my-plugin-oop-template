<?php
class Advanced_Plugin_Database
{
    private static $table_name;

    public function __construct()
    {
        global $wpdb;
        self::$table_name = $wpdb->prefix . 'custom_data';
    }

    public static function create_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_data';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            post_id BIGINT(20) UNSIGNED NOT NULL,
            additional_data TEXT NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function drop_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_data';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }

    public function save_data($post_id, $data, $is_update = false)
    {
        global $wpdb;

        if ($is_update) {
            return $wpdb->update(
                self::$table_name,
                ['additional_data' => 'Обновлено: ' . $data],
                ['post_id' => $post_id]
            );
        } else {
            return $wpdb->insert(
                self::$table_name,
                [
                    'post_id' => $post_id,
                    'additional_data' => 'Создано: ' . $data,
                ]
            );
        }
    }

    public function get_all_data()
    {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM " . self::$table_name);
    }

    public function delete_data($post_id)
    {
        global $wpdb;
        return $wpdb->delete(
            self::$table_name,
            ['post_id' => $post_id]
        );
    }

    public function update_data($post_id, $data)
    {
        global $wpdb;
        return $wpdb->update(
            self::$table_name,
            ['additional_data' => $data],
            ['post_id' => $post_id]
        );
    }
}
