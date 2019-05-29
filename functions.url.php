<?php
/**
 * URL FUNCTIONS
 *
 * @description      This file contain functions for the URL handling
 *
 * @author           Ivijan-Stefan Stipic <creativform@gmail.com>
 * @version          1.0.0
**/

/* Check is SSL active */
if(!function_exists('is_ssl')) :
	function is_ssl() {
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
			return true;
		else if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
			return true;
		else if(!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on')
			return true;
		else if(isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
			return true;
		else if(isset($_SERVER['HTTP_X_FORWARDED_PORT']) && $_SERVER['HTTP_X_FORWARDED_PORT'] == 443)
			return true;
		else if(isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https')
			return true;
		
        return false;
    }
endif;

/* Get current URL */
if(!function_exists('get_current_url')) :
	function get_current_url() {
		$host = (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');
		$uri = (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/');
		$s = is_ssl() ? 's' : NULL;
        return "http{$s}://{$host}{$uri}";
	}
endif;


/* Test is AJAX call */
if(!function_exists('is_ajax')) :
	function is_ajax() {		
		return ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strcasecmp($_SERVER['HTTP_X_REQUESTED_WITH'], 'xmlhttprequest') == 0 );
	}
endif;

/* Generate slug format from the string */
if(!function_exists('to_slug')) :
function to_slug( $str ) {
		$str = strtolower((string)$str);
        $str = trim($str);
        return preg_replace(array(
            '/\W/',
            '/-{2,}/'
        ), '-', $str);
    }
endif;