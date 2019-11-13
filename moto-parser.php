<?php
/**
 * Plugin Name: Moto Parser
 * Description: Now Wordpress has ability to parsing prises for Motoworld Products
 * Author:      nd2021, Chloyka
 */

defined( 'ABSPATH' ) || exit;

require_once 'initialize.php';

class parserPage {
    function __construct ()
    {
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'register_parser_assets'));
    }

    function admin_menu ()
    {
        add_menu_page('Parsing Page', 'Moto Parser', 'manage_options', 'parser', array($this, 'settings_page'));
    }

    function  settings_page ()
    {
        require_once 'parser.php';
    }

    public function register_parser_assets ()
    {
        wp_register_style('bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
        wp_register_style('font-awesome', '//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        wp_register_style('parser', plugins_url('moto-parser/assets/css/style.css'));
        wp_register_style('select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css');
        wp_deregister_script('jquery');
        wp_register_script('jquery', '//code.jquery.com/jquery-latest.min.js');
        wp_register_script('popper', '//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', array('jquery'), null, false);
        wp_register_script('bootstrap', '//stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array('popper'), null, false);
        wp_register_script('dry-functions', plugins_url('moto-parser/assets/js/dry.functions.js'), array('jquery'), null, false);
        wp_register_script('parser-vars', plugins_url('moto-parser/assets/js/vars.js'), array('jquery'), null, false);
        wp_register_script('parser-scripts', plugins_url('moto-parser/assets/js/main.js'), array('jquery'), null, false);
        wp_register_script('select2', '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/js/select2.min.js', array('jquery'), null, false);
        wp_enqueue_style('bootstrap');
        wp_enqueue_style('font-awesome');
        wp_enqueue_style('parser');
        wp_enqueue_style('select2');
        wp_enqueue_script('jquery');
        wp_enqueue_script('popper');
        wp_enqueue_script('bootstrap');
        wp_enqueue_script('dry-functions');
        wp_enqueue_script('parser-vars');
        wp_enqueue_script('parser-scripts');
        wp_enqueue_script('select2');
    }
}
new parserPage;
