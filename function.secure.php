<?php
/**
* SECURE TOKEN GENERATOR
* Tokens should be created using a cryptographically secure random number generator.
  If they are made with rand, the state of the random number generator can be cracked trivially
  in many cases, and tokens can be predicted. On Linux it is a little bit harder to predict tokens,
  but this does still not give secure tokens. The random number generator on Windows is particularly
  easy to exploit, since any state of the random number generator can be cracked within minutes.
*
* @author   Ivijan-Stefan Stipic <creativform@gmail.com>
* @version  1.0.0
*
* @param integer   $length       Length of random characters
* @param string    $separator    Character separator
* @param string    $return       array / echo / string (default empty return string)
* @return string, array or echo
**/
function secure_token ($length = 32, $separator = '', $return = '', $has_symbol = false)
{
	$token = []; 
	if($length < 0) $length = 0;
	
	if(function_exists('mt_rand'))
	{
		// `mt_rand` uses a better randomization algorithm (Mersenne Twist)
		while($length--){
			
			if($has_symbol)
				$choose = mt_rand(0, 3);
			else
				$choose = mt_rand(0, 2);
			
			if ($choose === 0)
				$token []= chr(mt_rand(ord('A'), ord('Z')));
			else if($choose === 1)
				$token []= chr(mt_rand(ord('a'), ord('z')));
			else if($choose === 2)
				$token []= chr(mt_rand(ord('0'), ord('9')));
			else {
				$char = array(ord('-'),ord('_'));
				$token []= chr($char[mt_rand(0, 1)]);
			}
		}
	}
	else
	{
		// Since PHP 7.1 `mt_rand` has superseded `rand` completely, and `rand` was made an alias for `mt_rand`
		// but if we run on the older PHP versions, we must have `rand` in any case that `mt_rand` missing
		while($length--){
			
			if($has_symbol)
				$choose = rand(0, 3);
			else
				$choose = rand(0, 2);
			
			if ($choose === 0)
				$token []= chr(rand(ord('A'), ord('Z')));
			else if($choose === 1)
				$token []= chr(rand(ord('a'), ord('z')));
			else if($choose === 2)
				$token []= chr(rand(ord('0'), ord('9')));
			else {
				$char = array(ord('-'),ord('_'));
				$token []= chr($char[rand(0, 1)]);
		}
	}
	
	if($return == 'array')
		$token;
	else if($return == 'echo')
		echo join($separator,$token);
	else
		return join($separator,$token);
    
}
