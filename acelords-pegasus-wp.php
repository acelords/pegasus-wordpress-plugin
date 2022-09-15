<?php
/*
 * Plugin Name: AceLords Project Pegasus WordPress Plugins
 * Plugin URI: https://github.com/acelords/pegasus-wordpress-plugin
 * Description: WordPress Plugins for complementing AceLords' Project Pegasus
 * Version: 1.6.5
 * Author: AceLords
 * Author URI: https://www.acelords.space
 */

define('ACELORDS_PEGASUS_WP_PLUGINS_VERSION', '1.6.5');

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
            'name' => 'home-order-form-one-oceanwp',
        ),
        $atts
    );

    $name = esc_attr( $args['name'] );

    $setValue = get_option( 'pegasus_setting_field' );
    if(! $setValue || $setValue == "") {
        $setValue = "https://app." . $_SERVER['SERVER_NAME'];
    }

    return '
        <script> var ACELORDS_PEGASUS_SYSTEM_URL = "' . $setValue . '";</script>
        <div id="acelords-pegasus-plugin-' . $name . '"></div>
    ';
}
add_shortcode('AceLordsPegasusPlugins', 'handle_shortcode');

// add vue and other scripts to frontend page
function acelords_pegasus_plugins_enqueue_scripts() {
    global $post;
    if(has_shortcode($post->post_content, "AceLordsPegasusPlugins")) {

        wp_enqueue_script( 'acelords-pegasus-plugin-js-manifest', plugin_dir_url( __FILE__ ) . 'public/js/manifest.js', [], ACELORDS_PEGASUS_WP_PLUGINS_VERSION, true);
        wp_enqueue_script( 'acelords-pegasus-plugin-js-vendor', plugin_dir_url( __FILE__ ) . 'public/js/vendor.js', [], ACELORDS_PEGASUS_WP_PLUGINS_VERSION, true);
        wp_enqueue_script( 'acelords-pegasus-plugin-js-app', plugin_dir_url( __FILE__ ) . 'public/js/app.js', [], ACELORDS_PEGASUS_WP_PLUGINS_VERSION, true);

        wp_enqueue_style( 'tailwind-css', plugin_dir_url( __FILE__ ) . 'public/css/tailwind.css', [], '1.9.5' );
        wp_enqueue_style( 'acelords-pegasus-plugin-app-css', plugin_dir_url( __FILE__ ) . 'public/css/app.css', [], ACELORDS_PEGASUS_WP_PLUGINS_VERSION );
        wp_enqueue_style( 'acelords-pegasus-plugin-google-fonts', 'https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400;0,700;1,400&display=swap' );
        wp_enqueue_style( 'mdi-fonts-css', plugin_dir_url( __FILE__ ) . 'public/fonts/mdi/css/materialdesignicons.min.css', [], '5.0.45' );

    }
}
add_action('wp_enqueue_scripts', 'acelords_pegasus_plugins_enqueue_scripts');

// Admin Section
if( ! function_exists("acelords_pegasus_wordpress_plugins_page") ) {

    function acelords_pegasus_wordpress_plugins_page($content) {
        ?>
            <div class="tw-w-full" style="font-family: Prata,Roboto,serif;">
                <h3 class="tw-text-3xl tw-text-gray-900 tw-text-center font-prata">AceLords Project-Pegasus WordPress Plugins</h3>
                <p class="tw-text-center font-raleway">View and manage settings for AceLords plugins.  (v<?php echo ACELORDS_PEGASUS_WP_PLUGINS_VERSION ?>)</p>
            </div>

            <form method="POST" action="options.php" class="tw-flex tw-flex-wrap">
                <div class="">
                    <?php settings_fields( 'pegasus_setting_field' ); ?>
                </div>
                <div class="tw-flex">
                    <?php do_settings_sections( 'pegasus_setting_field' ); ?>
                </div>
                <div class="">
                    <?php submit_button(); ?>
                </div>
            </form>

            <div id="acelords-pegasus-wordpress-plugins-admin"></div>

        <?php
    }

    add_action( 'admin_init', 'pegasus_wordpress_settings_init' );

    function pegasus_wordpress_settings_init() {

        add_settings_section(
            'pegasus_setting_section',
            __( 'Pegasus System URL', 'pegasus-wordpress' ),
            'pegasus_setting_section_callback_function',
            'pegasus_setting_field'
        );

        add_settings_field(
            'pegasus_setting_field',
            __( 'Pegasus System URL', 'pegasus-wordpress' ),
            'pegasus_setting_markup',
            'pegasus_setting_field',
            'pegasus_setting_section'
        );

        register_setting( 'pegasus_setting_field', 'pegasus_setting_field' );
    }

    function pegasus_setting_section_callback_function() {
        echo '<p>Set the URL in which the plugins shall fetch their data.</p>';
    }

    function pegasus_setting_markup() {
        $setValue = get_option( 'pegasus_setting_field' );
        if(! $setValue || $setValue == "") {
            $setValue = "https://app." . $_SERVER['SERVER_NAME'];
        }
        ?>
            <!-- <label for="pegasus_setting_field"><?php // _e( 'Pegasus System URL' ); ?></label> -->
            <input type="url" id="pegasus_setting_field" placeholder="<?php echo $setValue; ?>" name="pegasus_setting_field" value="<?php echo $setValue; ?>" style="width: 100%;">
        <?php
    }

    function acelords_pegasus_wordpress_plugins_menu() {
        $page_title = 'AceLords Pegasus WordPress Plugins';
        $menu_title = 'AceLords Pegasus';
        $capability = 'manage_options';
        $menu_slug  = 'acelords-pegasus-wp';
        $function   = 'acelords_pegasus_wordpress_plugins_page';
        $icon_url   = plugins_url( 'acelords-pegasus-wp/assets/acelords-light-28.svg' );
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
        if(strpos($hook, "acelords-pegasus-wp") !== false) {
            wp_enqueue_script( 'acelords-pegasus-plugin-js-manifest', plugin_dir_url( __FILE__ ) . 'public/js/manifest.js', [], ACELORDS_PEGASUS_WP_PLUGINS_VERSION, true);
            wp_enqueue_script( 'acelords-pegasus-plugin-js-vendor', plugin_dir_url( __FILE__ ) . 'public/js/vendor.js', [], ACELORDS_PEGASUS_WP_PLUGINS_VERSION, true);
            wp_enqueue_script( 'acelords-pegasus-plugin-js-admin-app', plugin_dir_url( __FILE__ ) . 'public/js/admin-app.js', [], ACELORDS_PEGASUS_WP_PLUGINS_VERSION, true);

            wp_enqueue_style( 'acelords-pegasus-plugin-app-css', plugin_dir_url( __FILE__ ) . 'public/css/app.css', [], ACELORDS_PEGASUS_WP_PLUGINS_VERSION );
            wp_enqueue_style( 'tailwind-css', plugin_dir_url( __FILE__ ) . 'public/css/tailwind.css', [], '1.9.5' );
            wp_enqueue_style( 'mdi-fonts-css', plugin_dir_url( __FILE__ ) . 'public/fonts/mdi/css/materialdesignicons.min.css', [], '5.0.45' );
        }
    });
}


/**
 * Add menu entry on admin topbar
 */
add_action('admin_bar_menu', 'acelords_add_toolbar_items', 100);
function acelords_add_toolbar_items($admin_bar) {
    $admin_bar->add_menu( array(
        'id'    => 'acelords-top-bar-entry',
        'title' => 'AceLords Pegasus',
        'href'  => admin_url( 'admin.php?page=acelords-pegasus-wp' ),
        'meta'  => array(
            'title' => __('AceLords Pegasus'),
        ),
    ));
}

/**
 * plugin updater
 */
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/acelords/pegasus-wordpress-plugin/raw/master/plugin.json',
    __FILE__, // Full path to the main plugin file or functions.php.
    'acelords-pegasus-wp'
);
