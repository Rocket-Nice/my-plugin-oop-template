<?php

/**
 * Plugin Name: Advanced Plugin 
 * Description: Плагин с кастомным типом записи, таблицей, мета-боксом, AJAX действиями и страницами настроек.
 * Version: 1.1
 * Author: Ваше имя
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined('ABSPATH') || exit;

// Определение констант
define('ADVANCED_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('ADVANCED_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ADVANCED_PLUGIN_VERSION', '1.1');

// Подключаем необходимые файлы
require_once ADVANCED_PLUGIN_PATH . 'includes/class-database.php';
require_once ADVANCED_PLUGIN_PATH . 'includes/class-post-type.php';
require_once ADVANCED_PLUGIN_PATH . 'includes/class-meta-box.php';
require_once ADVANCED_PLUGIN_PATH . 'includes/class-admin-pages.php';
require_once ADVANCED_PLUGIN_PATH . 'includes/class-ajax-handler.php';
require_once ADVANCED_PLUGIN_PATH . 'includes/class-assets.php';

// Хуки активации/деактивации
register_activation_hook(__FILE__, ['Advanced_Plugin_Database', 'create_table']);
register_activation_hook(__FILE__, ['Advanced_Plugin_Post_Type', 'register']);
register_activation_hook(__FILE__, 'flush_rewrite_rules');

register_deactivation_hook(__FILE__, 'flush_rewrite_rules');

register_uninstall_hook(__FILE__, ['Advanced_Plugin_Database', 'drop_table']);
register_uninstall_hook(__FILE__, function () {
    delete_option('advanced_plugin_option');
});

// Инициализация классов
new Advanced_Plugin_Post_Type();
new Advanced_Plugin_Meta_Box();
new Advanced_Plugin_Admin_Pages();
new Advanced_Plugin_Ajax_Handler();
new Advanced_Plugin_Assets();
