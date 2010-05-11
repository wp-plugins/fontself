<?php
/*
Copyright (C) 2008 Claude Vedovini <http://vedovini.net/>.

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

define('FONTSELF_DOMAIN', 'fontself');
define('FONTSELF_VERSION', '1.0');
define('FONTSELFDIR', WP_PLUGIN_DIR . "/fontself/");
define('FONTSELFURL', WP_PLUGIN_URL . "/fontself/");


function fs_filter_comment($text = '') {
	global $post, $comment;

	$meta = fs_get_comment_meta($comment->comment_ID);

	if (!$meta) {
		return $text;
	}

	$id = $post->ID . "-" . $comment->comment_ID;
	return fs_filter($id, $text, $meta['font']);
}

function fs_filter($id, $content, $font, $size=20) {
	if (empty($font) || empty($content)) {
		return $content;
	}

	$div_id = "fontself-" . $id;
	// $raw_content = fs_normalize($content);
	$raw_content = html_entity_decode($raw_content, ENT_QUOTES, 'UTF-8');
	$raw_content = urlencode($raw_content);

	return <<<EOT
<div id="$div_id" class="fontself-enabled" font="$font" size="$size" content="$raw_content"></div>
<div id="alt-$div_id">$content</div>
EOT;
}

function fs_set_comment_meta($comment_id, $font) {
	global $wpdb;

	$table_name = $wpdb->prefix . "fontself_meta";
	$id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $table_name WHERE comment_id = %s", $comment_id));

	if (!$id) {
		$data = compact('font');
		$data['comment_id'] = $comment_id;
		$wpdb->insert($table_name, $data);
	} else {
		$data = compact('font');
		$where = compact('id');
		$wpdb->update($table_name, $data, $where);
	}
}

function fs_get_comment_meta($comment_id) {
	global $wpdb;

	$table_name = $wpdb->prefix . "fontself_meta";
	$select = $wpdb->prepare("SELECT font FROM $table_name WHERE comment_id = %d", $comment_id);
	return $wpdb->get_row($select, ARRAY_A);
}

function fs_delete_comment_meta($comment_id) {
	global $wpdb;

	$table_name = $wpdb->prefix . "fontself_meta";
	$wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE comment_id = %s", $comment_id));
}

function fs_install() {
	global $wpdb;

	$table_name = $wpdb->prefix . "fontself_meta";
	$sql = <<<EOT
CREATE TABLE $table_name (
	id bigint(20) unsigned NOT NULL auto_increment,
	comment_id bigint(20) unsigned default NULL,
	font varchar(200) default NULL,
	UNIQUE KEY id (id),
	UNIQUE INDEX content_id (comment_id),
	INDEX comment_id (comment_id),
	FOREIGN KEY (comment_id) REFERENCES $wpdb->comments (comment_ID) ON DELETE CASCADE
);
EOT;

	if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		add_option("fs_db_version", FONTSELF_VERSION);
	} else {
		$installed_ver = get_option("fs_db_version");

		if ($installed_ver != FONTSELF_VERSION) {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			update_option("fs_db_version", FONTSELF_VERSION);
		}
	}
}

if (!function_exists('json_encode')) {
    require_once(ABSPATH."/wp-includes/js/tinymce/plugins/spellchecker/classes/utils/JSON.php");
    
	function fs_json_encode($obj) {
		$json_obj = new Moxiecode_JSON();
		return $json_obj->encode($obj);
	}
} else {
    function fs_json_encode($obj) {
        return json_encode($obj);
    }
}

if (!function_exists('json_decode')) {
    require_once(ABSPATH."/wp-includes/js/tinymce/plugins/spellchecker/classes/utils/JSON.php");
    
	function fs_json_decode($json) {
        $json_obj = new Moxiecode_JSON();
    	$results = $json_obj->decode($json);
    	$out = array();
    	foreach ($results as $row) {
    		$out[] = (object) $row;
    	}
    	return $out;
    }
} else {
    function fs_json_decode($json) {
        return json_decode($json);
    }
}