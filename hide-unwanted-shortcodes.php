<?php
/*
Plugin Name: Hide Unwanted Shortcodes
Plugin URI: http://denzeldesigns.com
Version: 1.1
Description: A plugin to prevent unwanted shortcodes from showing on blog. This plugin does not remove shortcodes from Post Editor or Database, it merely hides it from public view.
Author: Denzel Chia
Author URI: http://denzeldesigns.com/
*/ 

//Create empty shortcodes class.
class hus_shortcode{
         function __construct($tag){
         $option = get_option('hus_options');
         $raw_shortcode_string = $option['shortcode_tags'];
         //activate add_shortcode only if option has values.
        	 if(!empty($raw_shortcode_string)){
        	 add_shortcode("$tag",array(&$this,'create_shortcode'),12);
         	}
         }
 		//function to create shortcode
 		function create_shortcode($content=null,$atts){ 		
 		return;		
 		}
}

function hus_do_hide_shortcodes(){
$option = get_option('hus_options');
$raw_shortcode_string = $option['shortcode_tags'];
$shortcode_array = explode(",",$raw_shortcode_string);
if(!empty($shortcode_array)){
		foreach($shortcode_array as $s){
			$o = new hus_shortcode($s);
		}
	}
}
add_action('init','hus_do_hide_shortcodes');

// Create settings page
// since version 1.0

add_action('admin_menu','hus_hook_plugin_menu');

function hus_hook_plugin_menu(){
//add options page
add_options_page('Hide Unwanted Shortcodes','Hide Unwanted Shortcodes','manage_options','hide-unwanted-shortcodes', 'hus_create_admin_page');
//register settings
register_setting('hus_plugin_options','hus_options','hus_validate_options');
}


function hus_create_admin_page(){
?>
<div class="wrap">
<?php screen_icon(); ?>
<h2>Hide Unwanted Shortcodes</h2>
<div>
<h4 class="description"><u>Instructions</u></h4>
<ol>
<li>Example shortcodes [nggallery id="123456"] or [caption] My Caption [/caption]</li>
<li>For shortcode [nggallery id="123456"] the shortcode tag is nggallery</li>
<li>For shortcode [caption] My Caption [/caption] the shortcode tag is caption</li>
<li>Fill the shortcode tag in the text area below, for multiple shortcode tags, separate them with a comma.</li>
<li>For example. nggallery,caption</li>
<li>Save Changes, and view "live" Blog to see that shortcodes are "removed".</li>
<li>If your shortcodes are one after another, example [shortcode1] [shortcode2],<br/> there may be a hidden break generated by WordPress, after the shortcodes are hidden.</li>
<li>Please note that this plugin does not remove the shortcodes from your database or post editor, <br/>instead it only "removes" them from your "live" Blog, by hiding them from Public View.</li>
</ol>
</div>

<br/>
<?php
global $shortcode_tags;
$tt = $shortcode_tags;
//print_r($tt);
?>
<form method="post" action="options.php">
 
            <?php
            //this settings_fields function will generate the wordpress nonce security check as hidden inputs
            settings_fields('hus_plugin_options');
            ?>
            
            <?php
            //get all options from options table
            $options = get_option('hus_options');
            ?>
            
<p>
<label><h4><u>Unwanted Shortcode Tags</u></h4></label>
<span class="description">Fill all unwanted shortcode tags in the textarea below, seperate each tag with a comma. Click "Save Changes" when you are done.</span></p>
<p>
<textarea name="hus_options[shortcode_tags]"  rows="18" cols="90"><?php echo $options['shortcode_tags']; ?></textarea>
</p>            
 
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
            
</form>
</div>
<?
}

function hus_validate_options($hus_options){
//start checking options
 
//sanitise, make sure no html tags!
$hus_options['shortcode_tags'] = wp_filter_nohtml_kses($hus_options['shortcode_tags']);

return $hus_options;
}
?>