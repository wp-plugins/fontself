<?php
/*
 Plugin Name: Fontself
 Plugin URI: http://vedovini.net/plugins
 Description: Allows posting, commenting and viewing with fontself.
 Version: 1.0
 Author: Claude Vedovini
 Author URI: http://vedovini.net/
 */

/*
Copyright (C) 2008 Fontself.com <http://www.fontself.com/>.

The Fontself Wordpress plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

The Fontself Wordpress plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with the Fontself Wordpress plugin.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once('fs_utils.php');
require_once('fs_api.php');


class Fontself {

	var $config;

	function Fontself() {
		add_action('activate_fontself/fontself.php', 'fs_install');
		add_action('init', array(&$this, 'init'));
	}

	function init() {
		update_option('fontself_version', FONTSELF_VERSION);

		// XXX: this may be a little too early
		load_plugin_textdomain(FONTSELF_DOMAIN, FONTSELFDIR);

		// Register and enqueue javascripts
		wp_register_script('swfobject', FONTSELFURL . 'js/swfobject.js', array(), '2.1');
		wp_register_script('fontself', FONTSELFURL . 'js/fontself.js', array('jquery', 'swfobject'), FONTSELF_VERSION);
		wp_enqueue_script('fontself');
		
        // Register and enqueue css
		wp_register_style('fontself', FONTSELFURL . 'style.css', false, FONTSELF_VERSION, 'all');
        wp_enqueue_style('fontself');
		
		// Init options
		add_option('fontself_comments', '1');

		// Hook up comments handling
		add_action('comment_form', array(& $this, 'comment_form'));
		add_filter('comment_post', array(& $this,'comment_post'), 1000, 2); // Try to be the last filter, 1000 should be a good figure

		// Hook up display handling
		add_filter('comment_text', 'fs_filter_comment', 1000);

		// Config initialization
		add_action('admin_menu', array(& $this, 'init_config'));
		add_action('admin_action_fs_signpost', 'fs_signpost');

		// Configure short-code
		add_shortcode('fontself', array(& $this, 'shortcode'));
		
		// Don't bother doing this stuff if the current user lacks permissions
		if (current_user_can('edit_posts') || current_user_can('edit_pages')) {
			// Add only in Rich Editor mode
			if ( get_user_option('rich_editing') == 'true') {
			    add_filter('mce_external_plugins', array(& $this, 'register_mce_plugin'));
			    add_filter('mce_buttons', array(& $this, 'register_mce_buttons'));
                add_action( 'admin_head', array(& $this, 'admin_font_list') );
			}
		}
	}
	
	function register_mce_plugin($plugins) {
	    $plugins['fontself'] = FONTSELFURL.'tinymce/editor_plugin.js';
	    return $plugins;
	}
    
    function register_mce_buttons($buttons) {
        array_push($buttons, "separator", "fontself");
        return $buttons;
    }
	
    function admin_font_list() {
        // Get the public fonts
        $fonts = fs_get_public_fonts(); ?>
<script type="text/javascript">
var fontlist = <?php echo fs_json_encode($fonts); ?>;
</script>
	<?php
	}
	
function comment_form($post_id) {
            // Get the public fonts
            $fonts = fs_get_public_fonts(); ?>
<div id="fontself-comment-form">
    <input type="hidden" name="fontself_nonce" value="<?php echo wp_create_nonce('fontself'); ?>" />
    <div class="fontself-check"><input type="checkbox" name="fontself_on" value="1" /><label><?php _e('Use Fontself', FONTSELF_DOMAIN); ?>:</label></div>
    <div class="fontself-selector"><img class="preview" height="20" src="<?php echo $fonts[0]->preview;?>"></img>
        <ul class="fontlist">
            <?php foreach ($fonts as $f): ?>
                <li id="<?php echo $f->key; ?>" class="option"><img height="20" src="<?php echo $f->preview; ?>"></img></li>
            <?php endforeach; ?>
        </ul>
        <input type="hidden" name="fontself_font" value="<?php echo $fonts[0]->key;?>" />
    </div>
</div>
    <?php
	}

	function init_config() {
		require_once('fs_config.php');
		$this->config = new FSConfig();

		add_options_page(__('Fonstself', FONTSELF_DOMAIN), __('Fontself', FONTSELF_DOMAIN), 'manage_options',
			'fontself_options', array(& $this->config, 'option_page'));
	}

	function comment_post($comment_id, $status) {
		if (get_option('fontself_comments')) {
			if (wp_verify_nonce($_POST['fontself_nonce'], 'fontself') &&
                isset($_POST['fontself_on'])) {
				$font_key = $_POST['fontself_font'];
				fs_set_comment_meta($comment_id, $font_key);
			}
		}
	}

	function shortcode($atts, $content = '') {
		$id = md5($content);
		// Make sure the internal shortcodes are processed
		$content = do_shortcode($content);
		return fs_filter($id, $content, $atts['font'], $atts['size']);
	}

}

$myFontself = new Fontself();

function fontself($content, $font, $size=20) {
	$id = md5($content);
	echo fs_filter($id, $content, $font, $size);
}