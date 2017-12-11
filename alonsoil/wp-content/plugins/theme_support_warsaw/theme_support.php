<?php
/*
Plugin Name: Theme Support
Plugin URI: http://themeforest.net/user/template_path
Description: This plugin is compatible with carshire wordpress themes. 
Author: Muhibbur Rashid
Author URI: http://themebunch.com
Version: 1.0
Text Domain: BUNCH_NAME
*/
if( !defined( 'BUNCH_TH_ROOT' ) ) define('BUNCH_TH_ROOT', plugin_dir_path( __FILE__ ));
if( !defined( 'BUNCH_TH_URL' ) ) define( 'BUNCH_TH_URL', plugins_url( '', __FILE__ ) );
if( !defined( 'BUNCH_NAME' ) ) define( 'BUNCH_NAME', 'warsaw' );
include_once( 'includes/loader.php' );