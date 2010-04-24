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


define('FONTSELF_API', 'http://fontself.com/api/');


function fs_normalize($content) {
    // remove HTML tags
    $content = preg_replace('/<\/?\w+[^>]*>/', '', $content);
    // normalize whitespaces
    $content = preg_replace('/\s\s+/', ' ', $content);
    return $content;
}

function fs_call($path, $params=false) {
	$url = FONTSELF_API . $path;
	
	if ($params) {
		$url = $url . '?' . http_build_query($params);
	}
	
	return $url;
}

function fs_sortfonts($a, $b) {
	$aname = strtolower($a->name);
	$bname = strtolower($b->name);
	
	if ($aname == $bname) return 0;
    return ($aname < $bname) ? -1 : 1;
}

function fs_get_public_fonts() {
	$url = fs_call('fonts/public/');
	$output = file_get_contents($url);
	
	if (empty($output)) {
		return array();
	} else {
		$fonts = json_decode($output);
        usort($fonts, 'fs_sortfonts');
        return $fonts;
	}
}