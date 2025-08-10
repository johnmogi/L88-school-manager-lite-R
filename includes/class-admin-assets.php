<?php
/**
 * Admin Assets Manager
 *
 * Handles enqueuing of admin CSS and JS files
 *
 * @package School_Manager_Lite
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class School_Manager_Lite_Admin_Assets {
    /**
     * The single instance of the class.
     */
    private static $instance = null;

    /**
     * Main Instance.
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Only load on School Manager pages
        if (strpos($hook, 'school-manager') === false) {
            return;
        }

        // Enqueue student types CSS
        wp_enqueue_style(
            'school-manager-student-types',
            plugin_dir_url(dirname(__FILE__)) . 'assets/css/student-types.css',
            array(),
            '1.0.0'
        );
    }
}

// Initialize the Admin Assets Manager
function School_Manager_Lite_Admin_Assets() {
    return School_Manager_Lite_Admin_Assets::instance();
}
School_Manager_Lite_Admin_Assets();
