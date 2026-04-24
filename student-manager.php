<?php
/**
 * Plugin Name: Student Manager
 * Description: Plugin quản lý sinh viên. Hỗ trợ Custom Post Type, Custom Meta Box và hiển thị danh sách qua Shortcode.
 * Version: 1.0.0
 * Author: Ngô Đức Dũng - 23810310264
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Ngăn chặn truy cập trực tiếp
}

// Định nghĩa hằng số đường dẫn để dễ gọi file
define( 'STUDENT_MANAGER_PATH', plugin_dir_path( __FILE__ ) );
define( 'STUDENT_MANAGER_URL', plugin_dir_url( __FILE__ ) );

// Nhúng các file xử lý logic
require_once STUDENT_MANAGER_PATH . 'includes/cpt-student.php';
require_once STUDENT_MANAGER_PATH . 'includes/shortcode-student.php';