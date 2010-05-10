<?php
/*
Plugin Name: Yr Weather
Plugin URI: http://wordpress.org/extend/plugins/yr-weather/
Description: Weather data from yr.no
Version: 1.0
Author: Jacob Friis Saxberg
Author URI: http://jacob.saxberg.dk/
License: GPL2
*/
/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

add_shortcode('wpyr', 'wpyr');
initYr();

function initYr () {
	// ToDo: Only load when plugin is in use
	$mydir = substr(dirname(__FILE__), strrpos(dirname(__FILE__), '/'));
	wp_enqueue_style('wpYr', WP_PLUGIN_URL . $mydir . '/style.css');
}

function wpyr ($attr) {
	global $yr_datadir;
	// defaults
	$flagdays = '';
	$yr_use_header = $yr_use_footer = false;
	$yr_use_banner = false; //yr.no Banner
	$yr_use_text = false;   //Tekstvarsel
	$yr_use_links = false;  //Lenker til varsel pÂ yr.no
	$yr_use_table = true;  //Tabellen med varselet
	$yr_link_target = '';
	$url = 'http://www.yr.no/place/Denmark/Capital/Copenhagen';
	$name = 'Copenhagen';
	// get parameters from user
	extract($attr);
	// fix url
	$url = trim($url);
	if (substr($url, -1, 1) == '/') {
		$url = substr($url, 0, -1);
	}
	// the user may not change these
	$yr_vis_php_feilmeldinger = false;
	$yr_maxage = 1200;
	//$yr_maxage = 0; /* for debug */
	$yr_timeout = 10;
	$yr_datadir = WP_CONTENT_DIR . '/cache/yr_cache';
	$yr_try_curl = true;
	// done
	include_once('yr.php');
	$yr_xmlparse = new YRComms();
	$yr_xmldisplay = new YRDisplay();
	$yr_xmldisplay->flagdays = explode(' ', $flagdays);
	return $yr_xmldisplay->generateHTMLCached($url, $name, $yr_xmlparse, $url, $yr_try_curl, $yr_use_header, $yr_use_footer, $yr_use_banner, $yr_use_text, $yr_use_links, $yr_use_table, $yr_maxage, $yr_timeout, $yr_link_target);
}
 
?>