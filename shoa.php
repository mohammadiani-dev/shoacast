<?php
/*
Plugin Name: Shoacast
Plugin URI: http://mohammadiani.com
Author: یوسف محمدیانی
Version: 1.0.0
Text Domain: shoacast
Domain Path: /languages
Author URI: http://mohammadiani.com
*/


define("SHOA_PATH" , plugin_dir_path(__FILE__));
define("SHOA_URI" , plugin_dir_url(__FILE__));
define("SHOA_VER" , "1.1.0");


require_once SHOA_PATH . 'inc/admin.php';

if(!is_admin()){
    require_once SHOA_PATH . 'inc/front.php';
}
