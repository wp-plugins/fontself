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


/* This should be renamed to avoid conflicts */
function setFlashHeight(o){
	var e = jQuery('#' + o.id);
	e.height(o.h + 'px');
	e.width('100%');
	jQuery('#alt-' + o.id).remove();
}

(function($) {
	$.fn.fontself = function() {
		return this.each(function() {
			var $this = $(this);
			var orig = $('#alt-' + $this.attr('id'));
			
			var fontSize = $this.attr('size');
			if (!fontSize) {
				fontSize = orig.css('font-size');
				fontSize = fontSize.match(/\d+/);
				fontSize = 2 * (fontSize ? fontSize[0] : 12);
			}
			
			var flashVars = {
				fkey: $this.attr('font'),
				size: fontSize,
				divId: $this.attr('id'),
				text: $this.attr('content')
			};
			
			var params = {
				play: true,
				loop: false,
				quality: 'best',
				allowScriptAccess: 'always',
				allowfullscreen: false
			};
			
			var attributes = { id: $this.attr('id') };
			var player = 'http://static.fontself.com/10175/swf/FSPlayer.swf';
			
			swfobject.embedSWF(player, $this.attr('id'), 
				'100%', '1px', '10.0.0', false, flashVars, params, attributes);
		});
	}
	
	$.fn.fontSelector = function(onSelect) {
		return this.each(function() {
			var $this = $(this);
			var $fontlist = $('.fontlist', this);
			
			$('.option', this).click(function() {
				onSelect.call($this, this.id, $('img', this).attr('src'));
				$fontlist.hide();
			});
			
			$this.hover(function() { $fontlist.show(); }, 
					function() { $fontlist.hide(); });
		});
	}

	$(document).ready(function() {
		$('.fontself-enabled').fontself();
		$('.fontself-selector').fontSelector(function(key, preview) {
			$('input', this).val(key);
			$('.preview', this).attr('src', preview);
		});
	});
})(jQuery);
