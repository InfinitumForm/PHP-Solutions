<?php
/**
 * PHP7 DEPRECATED FUNCTION SUPPORT
 *
 * @description      This file fix removed functionality from PHP7 and add them back on the new way
 *                   You just need to add this PHP file at the top of your enthire project and will
 *                   work nice for you.
 * @url              https://wiki.php.net/rfc/remove_deprecated_functionality_in_php7
 * @author           Ivijan-Stefan Stipic <creativform@gmail.com>
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
	 * @author           Ivijan-Stefan Stipic <creativform@gmail.com>
	**/
	if(!function_exists('call_user_method_array')):
		function call_user_method_array($method_name, &$obj, $params)
		{
			call_user_func_array(array($obj, $method_name), $params);
		}
	endif;
endif;

if (version_compare(PHP_VERSION, '7.2.0', '>='))
{
	/**
	 * @name             call_user_method_array
	 * @description      call_user_method_array — Call a user method given with an array of parameters (PHP 4 >= 4.0.1, PHP 5, PHP 7) (eval()  must be anabled)
	 * @url              http://php.net/manual/en/function.create-function.php
	 * @author           Ivijan-Stefan Stipic <creativform@gmail.com>
	**/
	if(!function_exists('create_function')):
		function create_function($args, $code)
		{
			return eval("function ( {$args} ) { {$code} }");
		}
	endif;
}
else if (version_compare(PHP_VERSION, '7.3.0', '>='))
{
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
}
else if (version_compare(PHP_VERSION, '7.4.0', '>='))
{
	/**
	 * @name             money_format
	 * @description      money_format — Formats a number as a currency string
	 * @url              https://www.php.net/manual/en/function.money-format.php
	 * @author           Nageen Nayak <https://stackoverflow.com/users/3996624/nageen-nayak>
	**/
	if(!function_exists('money_format'))
	{
		function money_format($format, $number) { 

			if (setlocale(LC_MONETARY, 0) == 'C') { 
				return number_format($number, 2); 
			}

			$locale = localeconv(); 

			$regex = '/^'.             // Inicio da Expressao 
					 '%'.              // Caractere % 
					 '(?:'.            // Inicio das Flags opcionais 
					 '\=([\w\040])'.   // Flag =f 
					 '|'. 
					 '([\^])'.         // Flag ^ 
					 '|'. 
					 '(\+|\()'.        // Flag + ou ( 
					 '|'. 
					 '(!)'.            // Flag ! 
					 '|'. 
					 '(-)'.            // Flag - 
					 ')*'.             // Fim das flags opcionais 
					 '(?:([\d]+)?)'.   // W  Largura de campos 
					 '(?:#([\d]+))?'.  // #n Precisao esquerda 
					 '(?:\.([\d]+))?'. // .p Precisao direita 
					 '([in%])'.        // Caractere de conversao 
					 '$/';             // Fim da Expressao 

			if (!preg_match($regex, $format, $matches)) { 
				trigger_error('Invalid format: '.$format, E_USER_WARNING); 
				return $number; 
			} 

			$options = array( 
				'preenchimento'   => ($matches[1] !== '') ? $matches[1] : ' ', 
				'nao_agrupar'     => ($matches[2] == '^'), 
				'usar_sinal'      => ($matches[3] == '+'), 
				'usar_parenteses' => ($matches[3] == '('), 
				'ignorar_simbolo' => ($matches[4] == '!'), 
				'alinhamento_esq' => ($matches[5] == '-'), 
				'largura_campo'   => ($matches[6] !== '') ? (int)$matches[6] : 0, 
				'precisao_esq'    => ($matches[7] !== '') ? (int)$matches[7] : false, 
				'precisao_dir'    => ($matches[8] !== '') ? (int)$matches[8] : $locale['int_frac_digits'], 
				'conversao'       => $matches[9] 
			); 

			if ($options['usar_sinal'] && $locale['n_sign_posn'] == 0) { 
				$locale['n_sign_posn'] = 1; 
			} elseif ($options['usar_parenteses']) { 
				$locale['n_sign_posn'] = 0; 
			} 
			if ($options['precisao_dir']) { 
				$locale['frac_digits'] = $options['precisao_dir']; 
			} 
			if ($options['nao_agrupar']) { 
				$locale['mon_thousands_sep'] = ''; 
			} 

			$tipo_sinal = $number >= 0 ? 'p' : 'n'; 
			if ($options['ignorar_simbolo']) { 
				$simbolo = ''; 
			} else { 
				$simbolo = $options['conversao'] == 'n' ? $locale['currency_symbol'] 
													   : $locale['int_curr_symbol']; 
			} 
			$numero = number_format(abs($number), $locale['frac_digits'], $locale['mon_decimal_point'], $locale['mon_thousands_sep']); 


			$sinal = $number >= 0 ? $locale['positive_sign'] : $locale['negative_sign']; 
			$simbolo_antes = $locale[$tipo_sinal.'_cs_precedes']; 

			$espaco1 = $locale[$tipo_sinal.'_sep_by_space'] == 1 ? ' ' : ''; 

			$espaco2 = $locale[$tipo_sinal.'_sep_by_space'] == 2 ? ' ' : ''; 

			$formatted = ''; 
			switch ($locale[$tipo_sinal.'_sign_posn']) { 
			case 0: 
				if ($simbolo_antes) { 
					$formatted = '('.$simbolo.$espaco1.$numero.')'; 
				} else { 
					$formatted = '('.$numero.$espaco1.$simbolo.')'; 
				} 
				break; 
			case 1: 
				if ($simbolo_antes) { 
					$formatted = $sinal.$espaco2.$simbolo.$espaco1.$numero; 
				} else { 
					$formatted = $sinal.$numero.$espaco1.$simbolo; 
				} 
				break; 
			case 2: 
				if ($simbolo_antes) { 
					$formatted = $simbolo.$espaco1.$numero.$sinal; 
				} else { 
					$formatted = $numero.$espaco1.$simbolo.$espaco2.$sinal; 
				} 
				break; 
			case 3: 
				if ($simbolo_antes) { 
					$formatted = $sinal.$espaco2.$simbolo.$espaco1.$numero; 
				} else { 
					$formatted = $numero.$espaco1.$sinal.$espaco2.$simbolo; 
				} 
				break; 
			case 4: 
				if ($simbolo_antes) { 
					$formatted = $simbolo.$espaco2.$sinal.$espaco1.$numero; 
				} else { 
					$formatted = $numero.$espaco1.$simbolo.$espaco2.$sinal; 
				} 
				break; 
			} 

			if ($options['largura_campo'] > 0 && strlen($formatted) < $options['largura_campo']) { 
				$alinhamento = $options['alinhamento_esq'] ? STR_PAD_RIGHT : STR_PAD_LEFT; 
				$formatted = str_pad($formatted, $options['largura_campo'], $options['preenchimento'], $alinhamento); 
			} 

			return $formatted; 
		}
	}
	
	/**
	 * @name             array_key_exists
	 * @description      array_key_exists — Checks if the given key or index exists in the array
	 * @url              https://www.php.net/manual/en/function.array-key-exists.php
	 * @author           Ivijan-Stefan Stipic <creativform@gmail.com>
	**/
	if(!function_exists('array_key_exists'))
	{
		function array_key_exists($key, $array){
			return is_array($array) ? isset($array[$key]) : (is_object($array) ? method_exists($array, $key) : false);
		}
	}
}

/**
 * @name             is_a
 * @description      is_a — Checks if the object is of this class or has this class as one of its parents
 * @info             On the some PHP versions this function not exists. On that case we must return it back
 * @url              https://www.php.net/manual/en/function.is-a.php
 * @author           Ivijan-Stefan Stipic <creativform@gmail.com>
**/
if(!function_exists('is_a'))
{
	function is_a($object, $class_name, $allow_string = false)
	{
		if($allow_string === false)
			return ($object instanceof $class_name);
		else
			return is_subclass_of($object, $class_name, true);
	}
}

/**
 * @name             Return missing constants
 * @description      This will give some older PHP versions constants that missing.
 * @author           Ivijan-Stefan Stipic <creativform@gmail.com>
**/
// Return WEBP constant
if(!defined(IMAGETYPE_WEBP)) {
	define('IMAGETYPE_WEBP', 18);
}
// Return ICO constant
if(!defined(IMAGETYPE_ICO)) {
	define('IMAGETYPE_ICO', 17);
}
// Fix missing PHP SESSION constant PHP_SESSION_NONE (this is bug on the some Nginx servers)
if (!defined('PHP_SESSION_NONE')) {
	define('PHP_SESSION_NONE', -1);
}

/**
 * @name             MySQL
 * @description      Let's bring MySQL deprecated support on new way
 * @url              http://php.net/manual/en/book.mysql.php
 * @author           Ivijan-Stefan Stipic <creativform@gmail.com>
**/
$php7x_mysql_connect = NULL;
if(!function_exists('mysql_connect'))
{
	function mysql_connect($server, $user, $password){
		global $php7x_mysql_connect;
		$php7x_mysql_connect = mysqli_connect($server, $user, $password);
		return $php7x_mysql_connect;
	}
}
if(!function_exists('mysql_select_db'))
{
	function mysql_select_db($db){
		global $php7x_mysql_connect;
		return mysqli_select_db($php7x_mysql_connect, $db);
	}
}
if(!function_exists('mysql_query'))
{
	function mysql_query($query){
		global $php7x_mysql_connect;
		return mysqli_query($php7x_mysql_connect, $query);
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
		global $php7x_mysql_connect;
		return mysqli_set_charset($php7x_mysql_connect, $charset);
	}
}
if(!function_exists('mysql_real_escape_string'))
{
	function mysql_real_escape_string($string){
		global $php7x_mysql_connect;
		return mysqli_real_escape_string($php7x_mysql_connect, $string);
	}
}
if(!function_exists('mysql_insert_id'))
{
	function mysql_insert_id() {
		global $php7x_mysql_connect;
		return mysqli_insert_id($php7x_mysql_connect);
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
		global $php7x_mysql_connect;
		return mysqli_affected_rows($php7x_mysql_connect);
	}
}
if(!function_exists('mysql_get_server_info'))
{
	function mysql_get_server_info() {
		global $php7x_mysql_connect;
		return mysqli_get_server_info($php7x_mysql_connect);
	}
}
if(!function_exists('mysql_close'))
{
	function mysql_close($connect) {
		if(!$connect) {
			global $php7x_mysql_connect;
			$connect = $php7x_mysql_connect;
		}
		return mysqli_close($connect);
	}
}