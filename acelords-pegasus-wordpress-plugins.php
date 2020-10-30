<?php
/**
 * Plugin Name: AceLords Project Pegasus WordPress Plugins
 * Plugin URI: https://github.com/acelords/acelords-pegasus-plugins
 * Description: WordPress Plugins for complementing AceLords' Project Pegasus 
 * Version: 1.0.6
 * Author: AceLords
 * Author URI: https://www.acelords.space
 */

/**
 * The constructor, to initiate the widget
 * The form() function to create the widget form in the administration
 * The update() function, to save widget data during edition
 * And the widget() function to display the widget content on the front-end
 *
 * https://www.wpexplorer.com/create-widget-plugin-wordpress/
 */

// Register Shortcode
function handle_shortcode($atts = '') {
    $args = shortcode_atts( 
        array(
            'name' => 'oceanwp-home-order-form',
        ), 
        $atts
    );

    $name = esc_attr( $args['name'] );
    
    return '<div id="acelords-pegasus-plugin-' . $name . '"></div>';
}
add_shortcode('AceLordsPegasusPlugins', 'handle_shortcode');

// add vue and other scripts to frontend page
function acelords_pegasus_plugins_enqueue_scripts() {
    global $post;
    if(has_shortcode($post->post_content, "AceLordsPegasusPlugins")) {

        wp_enqueue_script( 'acelords-pegasus-plugin-js-manifest', plugin_dir_url( __FILE__ ) . 'public/js/manifest.js', [], '1.0', true);
        wp_enqueue_script( 'acelords-pegasus-plugin-js-vendor', plugin_dir_url( __FILE__ ) . 'public/js/vendor.js', [], '1.0', true);
        wp_enqueue_script( 'acelords-pegasus-plugin-js-app', plugin_dir_url( __FILE__ ) . 'public/js/app.js', [], '1.0', true);

        wp_enqueue_style( 'tailwind-css', plugin_dir_url( __FILE__ ) . 'public/css/tailwind.css', [], '1.9.5' );
        wp_enqueue_style( 'acelords-pegasus-plugin-app-css', plugin_dir_url( __FILE__ ) . 'public/css/app.css', [], '1.0.0' );
        wp_enqueue_style( 'acelords-pegasus-plugin-google-fonts', 'https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap' );
        wp_enqueue_style( 'acelords-pegasus-plugin-mdi-font', plugin_dir_url( __FILE__ ) . 'public/fonts/mdi/css/materialdesignicons.min.css' );
        wp_enqueue_style( 'mdi-fonts-css', plugin_dir_url( __FILE__ ) . 'public/fonts/mdi/css/materialdesignicons.min.css', [], '5.0.45' );

    }
}
add_action('wp_enqueue_scripts', 'acelords_pegasus_plugins_enqueue_scripts');

// Admin Section
if( ! function_exists("acelords_pegasus_wordpress_plugins_page") ) {

    function acelords_pegasus_wordpress_plugins_page($content) {
        echo '<div id="acelords-pegasus-wordpress-plugins-admin"></div>';
    }

    function acelords_pegasus_wordpress_plugins_menu() {
        $page_title = 'AceLords Pegasus WordPress Plugins';
        $menu_title = 'AceLords Pegasus WP';
        $capability = 'manage_options';
        $menu_slug  = 'acelords-pegasus-wordpress-plugins';
        $function   = 'acelords_pegasus_wordpress_plugins_page';
        $icon_url   = 'dashicons-nametag';
        $position   = 4;

        add_menu_page(
            $page_title,
            $menu_title,
            $capability,
            $menu_slug,
            $function,
            $icon_url,
            $position
        );
    }
    add_action( 'admin_menu', 'acelords_pegasus_wordpress_plugins_menu' );

    // enqueue admin scripts
    add_action("admin_enqueue_scripts", function ($hook) {
        wp_enqueue_script( 'acelords-pegasus-plugin-js-manifest', plugin_dir_url( __FILE__ ) . 'public/js/manifest.js', [], '1.0', true);
        wp_enqueue_script( 'acelords-pegasus-plugin-js-vendor', plugin_dir_url( __FILE__ ) . 'public/js/vendor.js', [], '1.0', true);
        wp_enqueue_script( 'acelords-pegasus-plugin-js-admin-app', plugin_dir_url( __FILE__ ) . 'public/js/admin-app.js', [], '1.0', true);

        wp_enqueue_style( 'acelords-pegasus-plugin-app-css', plugin_dir_url( __FILE__ ) . 'public/css/app.css', [], '1.0.0' );
        wp_enqueue_style( 'tailwind-css', plugin_dir_url( __FILE__ ) . 'public/css/tailwind.css', [], '1.9.5' );
        wp_enqueue_style( 'mdi-fonts-css', plugin_dir_url( __FILE__ ) . 'public/fonts/mdi/css/materialdesignicons.min.css', [], '5.0.45' );
    });
}

/**
 * Plugin Updater
 */
//if( ! class_exists( 'AceLords_Pegasus_WordPress_Plugin_Updater' ) ) {
//    include_once( plugin_dir_path( __FILE__ ) . 'updater.php' );
//}


require_once( 'AceLordsGitHubPluginUpdater.php' );
if ( is_admin() ) {
    new AceLordsGitHubPluginUpdater( __FILE__, 'acelords', "pegasus-wordpress-plugin" );
}
//$updater = new AceLords_Pegasus_WordPress_Plugin_Updater( __FILE__ );
//$updater->set_username( 'acelords' );
//$updater->set_repository( 'pegasus-wordpress-plugin' );
////$updater->authorize( 'SECRET_KEY' ); // Your auth code goes here for private repos
//$updater->initialize();
