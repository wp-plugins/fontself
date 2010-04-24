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

(function() {
	// Load plugin specific language pack
	// tinymce.PluginManager.requireLangPack('fontself');

	tinymce.create('tinymce.plugins.FontselfPlugin', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			this.url = url;
			this.editor = ed;
		},

		/**
		 * Creates control instances based in the incomming name. This method is normally not
		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
		 * method can be used to create those.
		 *
		 * @param {String} n Name of the control to create.
		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
		 * @return {tinymce.ui.Control} New control instance or null if no control was created.
		 */
		createControl : function(n, cm) {
			if (n == 'fontself') {
				var ed = this.editor;
				
				function applyFont(key) {
					ed.execCommand('mceReplaceContent', false, '[fontself font="' + key + '"]{$selection}[/fontself]');
				}
				
				var c = cm.createListBox('fontself', {
					title : 'Fontself fonts',
					onselect: applyFont
				});

				tinymce.each(fontlist, function(v) {
					c.add(v.name, v.key);
				});

				return c;			
			}
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'Fontself plugin',
				author : 'Claude Vedovini',
				authorurl : 'http://vedovini.net',
				infourl : 'http://vedovini.net/plugins',
				version : '1.0'
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('fontself', tinymce.plugins.FontselfPlugin);
})();
