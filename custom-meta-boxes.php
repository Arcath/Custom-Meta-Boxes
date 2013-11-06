<?php
/*
Script Name: 	Custom Metaboxes and Fields
Contributors: 	Andrew Norcross ( @norcross / andrewnorcross.com )
				Jared Atchison ( @jaredatch / jaredatchison.com )
				Bill Erickson ( @billerickson / billerickson.net )
				Human Made Limited ( @humanmadeltd / hmn.md )
				Jonathan Bardo ( @jonathanbardo / jonathanbardo.com )
Description: 	This will create metaboxes with custom fields that will blow your mind.
Version: 	1.0 - Beta 1
*/

/**
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

/**
 * Defines the url to which is used to load local resources.
 * This may need to be filtered for local Window installations.
 * If resources do not load, please check the wiki for details.
 */

if ( ! defined( 'CMB_PATH') )
	define( 'CMB_PATH', str_replace( '\\', '/', dirname( __FILE__ ) ) );

if ( ! defined( 'CMB_URL' ) )
	define( 'CMB_URL', str_replace( str_replace( '\\', '/', WP_CONTENT_DIR ), str_replace( '\\', '/', WP_CONTENT_URL ), CMB_PATH ) );

include_once( CMB_PATH . '/classes.fields.php' );
include_once( CMB_PATH . '/class.cmb.php' );
include_once( CMB_PATH . '/class.cmb-post.php' );
include_once( CMB_PATH . '/class.cmb-option.php' );
include_once( CMB_PATH . '/class.cmb-group.php' );

include_once( CMB_PATH . '/example-functions.php' );

/**
 * Get all the meta boxes on init
 * 
 * @return null
 */
function cmb_init() {

	if ( ! is_admin() )
		return;

	// Load translations
	$textdomain = 'cmb';
	$locale = apply_filters( 'plugin_locale', get_locale(), $textdomain );

	// By default, try to load language files from /wp-content/languages/custom-meta-boxes/
	load_textdomain( $textdomain, WP_LANG_DIR . '/custom-meta-boxes/' . $textdomain . '-' . $locale . '.mo' );
	load_textdomain( $textdomain, CMB_PATH . '/languages/' . $textdomain . '-' . $locale . '.mo' );

	$meta_boxes = apply_filters( 'cmb_meta_boxes', array() );

	if ( ! empty( $meta_boxes ) )
		foreach ( $meta_boxes as $meta_box ) {
			new CMB_Post( $meta_box );
			// new CMB_Options( $meta_box );
		}

}
add_action( 'init', 'cmb_init' );

/**
 * Get a field class by type
 * 
 * @param  string $type 
 * @return string $class, or false if not found.
 */
function _cmb_field_class_for_type( $type ) {

	$map = apply_filters( 
		'cmb_field_types', 
		array(
			'text'				=> 'CMB_Text_Field',
			'text_small' 		=> 'CMB_Text_Small_Field',
			'text_url'			=> 'CMB_URL_Field',
			'url'				=> 'CMB_URL_Field',
			'radio'				=> 'CMB_Radio_Field',
			'checkbox'			=> 'CMB_Checkbox',
			'file'				=> 'CMB_File_Field',
			'image' 			=> 'CMB_Image_Field',
			'wysiwyg'			=> 'CMB_wysiwyg',
			'textarea'			=> 'CMB_Textarea_Field',
			'textarea_code'		=> 'CMB_Textarea_Field_Code',
			'select'			=> 'CMB_Select',
			'taxonomy_select'	=> 'CMB_Taxonomy',
			'post_select'		=> 'CMB_Post_Select',
			'date'				=> 'CMB_Date_Field',
			'date_unix'			=> 'CMB_Date_Timestamp_Field',
			'datetime_unix'		=> 'CMB_Datetime_Timestamp_Field',
			'time'				=> 'CMB_Time_Field',
			'colorpicker'		=> 'CMB_Color_Picker',
			'title'				=> 'CMB_Title',
			'group'				=> 'CMB_Group_Field'
		) 
	);

	if ( isset( $map[$type] ) )
		return $map[$type];

	return false;

}
