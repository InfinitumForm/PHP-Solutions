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
function secure_token ($length = 32, $separator = '', $return = '')
{
	$token = []; 
	if($length < 0) $length = 0;
	
	while($length--){
		$choose = rand(0, 2);
		if ($choose === 0)
			$token []= chr(rand(ord('A'), ord('Z')));
		else if($choose === 1)
			$token []= chr(rand(ord('a'), ord('z')));
		else
			$token []= chr(rand(ord('0'), ord('9')));
	}
	
	if($return == 'array')
		$token;
	else if($return == 'echo')
		echo join($separator,$token);
	else
		return join($separator,$token);
    
}
