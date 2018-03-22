<?php
/**
 * PHP7 DEPRECATED FUNCTION SUPPORT
 *
 * @description      This file fix removed functionality from PHP7 and add them back on the new way
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
