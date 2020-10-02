<?php
/**
* IS IP IN RANGE
* This function check is IP address equal or in range.
* This function only work for the IPv4 for now but I working on the IPv6 version also.
*
* @author   Ivijan-Stefan Stipic <creativform@gmail.com>
* @version  1.0.0
*
* @param string    $ip           IPv4 address what you want to check
* @param array     $range        Array of the IP addresses you want to check.
*
* Example:
*
*	is_ip_in_range( $ip, array(
*		'50.16.241.113'		=>	'50.16.241.117',
*		'54.208.100.253'	=>	'54.208.102.37',
*		'23.21.227.69',
*		'40.88.21.235'
*		'216.239.32.0'		=>	'216.239.63.255'
*	));
*
*
* @return string (finded IP address) or boolean false
**/
function is_ip_in_range( $ip, $range ){

	if(!is_array($range)) return false;

	// Let's search first single one
	ksort($range);
	
	// We need numerical representation of the IP
	$ip2long = ip2long($ip);
	
	// Non IP values needs to be removed
	if($ip2long !== false)
	{
		// Let's loop
		foreach($range as $start => $end)
		{
			// Convert to numerical representations as well
			$end = ip2long($end);
			$start = ip2long($start);
			$is_key = ($start === false);
			
			// Remove bad one
			if($end === false) continue;
			
			// Here we looking for single IP does match
			if(is_numeric($start) && $is_key && $end === $ip2long)
			{
				return $ip;
			}
			else
			{
				// And here we have check is in the range
				if(!$is_key && $ip2long >= $start && $ip2long <= $end)
				{
					return $ip;
				}
			}
		}
	}
	
	// Ok, it's not finded
	return false;
}
