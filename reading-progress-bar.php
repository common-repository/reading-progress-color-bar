<?php
/*
Plugin Name: Reading Progress Color Bar
Description: Reading Progress Bar Plugin adds a stylish progress bar at the top of your browser window as users read through your pages or posts.
Version: 1.3
Author: Anowar Hossain Rana
Author URI: https://cxrana.wordpress.com/
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Enqueue the necessary scripts and styles
function rpbp_enqueue_scripts() {
    if (is_singular('post') || is_page()) {
        $color = get_option('rpbp_bar_color', '#3498db'); // Default color

        // Define the version number based on the file modification time for the CSS file
        $style_version = filemtime(plugin_dir_path(__FILE__) . 'rpbp-style.css');

        // Enqueue the style with the version number
        wp_enqueue_style( 'rpbp-style', plugin_dir_url( __FILE__ ) . 'rpbp-style.css', array(), $style_version );

        wp_add_inline_style( 'rpbp-style', '#rpbp-progress-bar { background-color: ' . esc_attr($color) . '; }' );

        // Define the version number based on the file modification time for the JS file
        $script_version = filemtime(plugin_dir_path(__FILE__) . 'rpbp-script.js');

        // Enqueue the script with the version number
        wp_enqueue_script( 'rpbp-script', plugin_dir_url( __FILE__ ) . 'rpbp-script.js', array('jquery'), $script_version, true );
    }
}
add_action( 'wp_enqueue_scripts', 'rpbp_enqueue_scripts' );



// Add the progress bar to posts and pages, excluding the homepage
function rpbp_add_progress_bar() {
    if (is_singular('post') || is_page()) {
        echo '<div id="rpbp-progress-bar"></div>';
    }
}
add_action( 'wp_footer', 'rpbp_add_progress_bar' );

// Create settings page
function rpbp_create_settings_page() {
    add_options_page(
        'Reading Progress Bar Settings',
        'Reading Progress Bar',
        'manage_options',
        'reading-progress-bar',
        'rpbp_settings_page_html'
    );
}
add_action( 'admin_menu', 'rpbp_create_settings_page' );

// Register settings
function rpbp_register_settings() {
    register_setting(
        'rpbp-settings-group', // Option group
        'rpbp_bar_color', // Option name
        'sanitize_hex_color' // Use built-in sanitization callback
    );
}
add_action( 'admin_init', 'rpbp_register_settings' );


// Sanitization callback function for hex color
function rpbp_sanitize_hex_color( $color ) {
    // Ensure the color is a valid hex color code
    if (preg_match('/^#[a-f0-9]{6}$/i', $color)) {
        return $color;
    }
    return ''; // Return an empty string if the input is invalid
}

// Settings page HTML
function rpbp_settings_page_html() {
    $colors = array(
        "#dd4f4f", "#082a7b", "#ade1d2", "#eeeaa2", "#ddbfa3", "#4d766e", "#639586",
        "#8ebcae", "#9ed1c2", "#91baa0", "#74937b", "#b0cebb", "#394c4f", "#415a5e",
        "#485f63", "#f1e5a3", "#20aeac", "#bacf91", "#003935", "#89cc04", "#c78b98",
        "#fff4e1", "#ffd1d7", "#f7a1c4", "#dc7f9b", "#d3c0c5", "#dff4e2", "#709163",
        "#e8ede6", "#f4e2df", "#d7cddc", "#d2dccd", "#08523c"
    );
    ?>
    <div class="wrap">
        <h1>Reading Progress Color Bar Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'rpbp-settings-group' );
            do_settings_sections( 'rpbp-settings-group' );
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Progress Bar Color</th>
                    <td>
                        <select name="rpbp_bar_color">
                            <?php
                            $selected_color = get_option('rpbp_bar_color', '#3498db');
                            foreach ($colors as $color) {
                                echo '<option value="' . esc_attr($color) . '" ' . selected($selected_color, $color, false) . ' style="background-color:' . esc_attr($color) . '; color: white;">' . esc_html($color) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
