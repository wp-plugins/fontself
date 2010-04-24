<?php
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


class FSConfig {

	function option_page() {
	?>
<div class="wrap">
<h2><?php _e('Fontself options', FONTSELF_DOMAIN); ?></h2>

<form method="post" action="options.php"><?php wp_nonce_field('update-options'); ?>

<table class="form-table">
	<tr valign="top">
		<th scope="row"><?php _e('Comments', FONTSELF_DOMAIN); ?></th>
		<td>﻿ <label for="fontself_comments"> <input name="fontself_comments"
			type="checkbox" id="fontself_comments" value="1"
			<?php checked('1', get_option('fontself_comments')); ?> /> <?php _e('Enable Fontself on comments', FONTSELF_DOMAIN); ?></label>
		<p><em><?php _e('Selecting this option enables Fontself users to post comments using Fontself fonts.', FONTSELF_DOMAIN); ?></em></p>
		</td>
	</tr>
</table>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="fontself_comments" />
<p class="submit"><input type="submit" name="submit"
	value="<?php _e('Save Changes'); ?>" /></p>
</form>
</div>
		<?php
	}
	
}

