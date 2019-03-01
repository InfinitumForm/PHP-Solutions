<?php
/**
 * PHP7 DEPRECATED FUNCTION SUPPORT
 *
 * @description      This file fix removed functionality from PHP7 and add them back on the new way
                     You just need to add this PHP file at the top of your enthire project and will
					 work nice for you.
 * @url              https://wiki.php.net/rfc/remove_deprecated_functionality_in_php7
 * @author            Ivijan-Stefan Stipic <creativform@gmail.com>
 * @version          1.0.0
**/
if (version_compare(PHP_VERSION, '7.0.0', '>=')):
	/**
	 * @name             split
	 * @description      split — Split string into array by regular expression (PHP 4, PHP 5)
	 * @url              http://php.net/manual/en/function.split.php
	 * @author            Ivijan-Stefan Stipic <creativform@gmail.com>
	**/
	if(!function_exists('split')):
		function split($pattern, $string, $limit = -1, $flags = 0)
		{
			if(@preg_match($pattern, NULL) === false){
				if($limit === -1) $limit = PHP_INT_MAX;
				return explode( $pattern , $string, $limit);
			} else {
				return preg_split( $pattern , $string, $limit, $flags);
			}
		}
	endif;
	
	/**
	 * @name             call_user_method
	 * @description      call_user_method — Call a user method on an specific object (PHP 4, PHP 5)
	 * @url              http://php.net/manual/en/function.call-user-method.php
	 * @author            Ivijan-Stefan Stipic <creativform@gmail.com>
	**/
	if(!function_exists('call_user_method')):
		function call_user_method($method_name, &$obj, $parameter)
		{
			$numargs = func_num_args();
			if ($numargs > 3) {
				$arg_list = func_get_args();
				
				unset($arg_list[0]);
				unset($arg_list[1]);
				unset($arg_list[2]);
				
				call_user_func(array($obj, $method_name), $parameter, $arg_list);
			} else {
				call_user_func(array($obj, $method_name), $parameter);
			}
		}
	endif;
	
	/**
	 * @name             call_user_method_array
	 * @description      call_user_method_array — Call a user method given with an array of parameters (PHP 4 >= 4.0.5, PHP 5)
	 * @url              http://php.net/manual/en/function.call-user-method-array.php
	 * @author            Ivijan-Stefan Stipic <creativform@gmail.com>
	**/
	if(!function_exists('call_user_method_array')):
		function call_user_method_array($method_name, &$obj, $params)
		{
			call_user_func_array(array($obj, $method_name), $params);
		}
	endif;
endif;

if (version_compare(PHP_VERSION, '7.2.0', '>=')):
	/**
	 * @name             call_user_method_array
	 * @description      call_user_method_array — Call a user method given with an array of parameters (PHP 4 >= 4.0.1, PHP 5, PHP 7) (eval()  must be anabled)
	 * @url              http://php.net/manual/en/function.create-function.php
	 * @author            Ivijan-Stefan Stipic <creativform@gmail.com>
	**/
	if(!function_exists('create_function')):
		function create_function($args, $code)
		{
			return eval('function('.$args.'){'.$code.'}');
		}
	endif;
endif;

if (version_compare(PHP_VERSION, '7.3.0', '>=')):
	/**
	 * @name             FILTER_FLAG_SCHEME_REQUIRED
	 * @description      Requires the URL to contain a scheme part. Used with: FILTER_VALIDATE_URL
	 * @url              http://php.net/manual/en/filter.filters.flags.php
	 * @author           Ivijan-Stefan Stipic <creativform@gmail.com>
	**/
	if(!defined('FILTER_FLAG_SCHEME_REQUIRED'))
	{
		define('FILTER_FLAG_SCHEME_REQUIRED',65536);
	}
	/**
	 * @name             FILTER_FLAG_HOST_REQUIRED
	 * @description      Requires the URL to contain a host part. Used with: FILTER_VALIDATE_URL
	 * @url              http://php.net/manual/en/filter.filters.flags.php
	 * @author           Ivijan-Stefan Stipic <creativform@gmail.com>
	**/
	if(!defined('FILTER_FLAG_HOST_REQUIRED'))
	{
		define('FILTER_FLAG_HOST_REQUIRED',131072);
	}
	/**
	 * @name             image2wbmp
	 * @description      image2wbmp — Output image to browser or file
	 * @url              http://php.net/manual/en/function.image2wbmp.php
	 * @author           Ivijan-Stefan Stipic <creativform@gmail.com>
	**/
	if(!function_exists('image2wbmp'))
	{
		function image2wbmp($image, $to=NULL, $foreground=0){
			return imagewbmp($image, $to, $foreground);
		}
	}
endif;

/**
 * @name             MySQL to MySQLi
 * @description      Let's bring MySQL deprecated support on new way
 * @url              http://php.net/manual/en/book.mysql.php
 * @author           Ivijan-Stefan Stipic <creativform@gmail.com>
**/
$_mysql_connect = NULL;
if(!function_exists('mysql_connect'))
{
	function mysql_connect($server, $user, $password){
		global $_mysql_connect;
		$_mysql_connect = mysqli_connect($server, $user, $password);
		return $_mysql_connect;
	}
}
if(!function_exists('mysql_select_db'))
{
	function mysql_select_db($db){
		global $_mysql_connect;
		return mysqli_select_db($_mysql_connect, $db);
	}
}
if(!function_exists('mysql_query'))
{
	function mysql_query($query){
		global $_mysql_connect;
		return mysqli_query($_mysql_connect, $query);
	}
}
if(!function_exists('mysql_fetch_object'))
{
	function mysql_fetch_object($result){
		return mysqli_fetch_object($result);
	}
}
if(!function_exists('mysql_fetch_array'))
{
	function mysql_fetch_array($result){
		return mysqli_fetch_array($result);
	}
}
if(!function_exists('mysql_fetch_row'))
{
	function mysql_fetch_row($result){
		return mysqli_fetch_row($result);
	}
}
if(!function_exists('mysql_num_rows'))
{
	function mysql_num_rows($result){
		return mysqli_num_rows($result);
	}
}
if(!function_exists('mysql_set_charset'))
{
	function mysql_set_charset($charset){
		global $_mysql_connect;
		return mysqli_set_charset($_mysql_connect, $charset);
	}
}
if(!function_exists('mysql_real_escape_string'))
{
	function mysql_real_escape_string($string){
		global $_mysql_connect;
		return mysqli_real_escape_string($_mysql_connect, $string);
	}
}
if(!function_exists('mysql_insert_id'))
{
	function mysql_insert_id() {
		global $_mysql_connect;
		return mysqli_insert_id($_mysql_connect);
	}
}
if(!function_exists('mysql_data_seek'))
{
	function mysql_data_seek($db_query, $row_number=0) {
		return mysqli_data_seek($db_query, $row_number);
	}
}
if(!function_exists('mysql_fetch_field'))
{
	function mysql_fetch_field($results) {
		return mysqli_fetch_field($results);
	}
}
if(!function_exists('mysql_free_result'))
{
	function mysql_free_result($results) {
		return mysqli_free_result($results);
	}
}
if(!function_exists('mysql_affected_rows'))
{
	function mysql_affected_rows() {
		global $_mysql_connect;
		return mysqli_affected_rows($_mysql_connect);
	}
}
if(!function_exists('mysql_get_server_info'))
{
	function mysql_get_server_info() {
		global $_mysql_connect;
		return mysqli_get_server_info($_mysql_connect);
	}
}
if(!function_exists('mysql_close'))
{
	function mysql_close($_mysql_connect) {
		return mysqli_close($_mysql_connect);
	}
}