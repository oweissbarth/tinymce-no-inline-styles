<?php
/**
 * Plugin Name: TinyMCE No inline styles
 * Description: This plugin prevents tinyMCE from adding inline styles to your html. It adds classes instead giving you full control.
 * Version: 0.1.3
 * Author: Oliver Weißbarth
 * Author URI: http://oweissbarth.de
 */
 
 defined( 'ABSPATH' ) or die('No script kiddies please!');
 

 
function tnis_tinymce_settings($settings){
	$settings["formats"] = '{' .
						'alignleft: [' .
							'{selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li, img,table,dl.wp-caption", classes: "alignleft"}' .
						'],' .
						'aligncenter: [' .
							'{selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li, img,table,dl.wp-caption", classes: "aligncenter"}' .
						'],' .
						'alignright: [' .
							'{selector: "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li, img,table,dl.wp-caption", classes: "alignright"}' .
						'],' .
						'strikethrough: {inline: "del"}' .
					'}';

	return $settings;
}
add_filter( 'tiny_mce_before_init', 'tnis_tinymce_settings' );


function tnis_add_editor_styles() {
    add_editor_style( plugins_url("tnis_styles.css", __FILE__) );
}
add_action( 'admin_init', 'tnis_add_editor_styles' );


function tnis_settings_page(){
    ?>
	<div class="wrap">
	<h2>TinyMCE no inline styles</h2>

	<form method="post" action="options.php">
	    <?php settings_fields( 'tnis_settings_group' ); ?>
	    <?php do_settings_sections( 'tnis_settings_group' ); ?>
	    <table class="form-table">
	        <tr valign="top">
	        <th scope="row">Enqueue stylesheet automatically</th>
	        <td><input type="checkbox" name="tnis_add_styles" <?php echo (strcmp(get_option('tnis_add_styles'), "on")==0 ? "checked" : "") ?> /></td>
	        </tr>
	    </table>
    
    <?php submit_button(); ?>
	</form>
	</div>
	<?php
}

function tnis_add_settings_page(){
    add_options_page( "TinyMCE no inline styles", "No inline styles", "manage_options", "tnis", "tnis_settings_page");
    
	add_action('admin_init', 'tnis_register_settings');
}
add_action('admin_menu', 'tnis_add_settings_page');


function tnis_register_settings(){
	register_setting('tnis_settings_group', 'tnis_add_styles');
}



function tnis_add_fronend_styles(){
    if(strcmp(get_option('tnis_add_styles'), "on")==0){
        wp_enqueue_style("tnis_styling", plugins_url("tnis_styles.css", __FILE__));
    }
}
add_action('wp_enqueue_scripts', 'tnis_add_fronend_styles');


require 'plugin-update-checker/plugin-update-checker.php';
$className = PucFactory::getLatestClassVersion('PucGitHubChecker');
$myUpdateChecker = new $className(
    'https://github.com/oweissbarth/tinymce-no-inline-styles/',
    __FILE__,
    'master'
);
