<?php
/*
 * PHP LDAP get user SID
 * @author Ivijan-Stefan Stipic <creativform@gmail.com>
 * @version 1.1.0 BETA
*/

function bin_2_sid($binary_sid) {
	$sid = NULL;
	if(strlen(decbin(~0)) == 64)
	{
		$parts = unpack('Crev/x/nidhigh/Nidlow', $binary_sid);
		$ssid = sprintf('S-%u-%d',  $parts['rev'], ($parts['idhigh']<<32) + $parts['idlow']);
		$parts = unpack('x8/V*', $binary_sid);
		if ($parts) $ssid .= '-';
		$sid.= join('-', $parts);
	}
	else
	{	
		$sid = 'S-';
		//$ADguid = $info[0]['objectguid'][0];
		$sidinhex = str_split(bin2hex($binary_sid), 2);
		// Byte 0 = Revision Level
		$sid = $sid.hexdec($sidinhex[0]).'-';
		// Byte 1-7 = 48 Bit Authority
		$sid = $sid.hexdec($sidinhex[6].$sidinhex[5].$sidinhex[4].$sidinhex[3].$sidinhex[2].$sidinhex[1]);
		// Byte 8 count of sub authorities - Get number of sub-authorities
		$subauths = hexdec($sidinhex[7]);
		//Loop through Sub Authorities
		for($i = 0; $i < $subauths; $i++) {
			$start = 8 + (4 * $i);
			// X amount of 32Bit (4 Byte) Sub Authorities
			$sid = $sid.'-'.hexdec($sidinhex[$start+3].$sidinhex[$start+2].$sidinhex[$start+1].$sidinhex[$start]);
		}
	}
	return $sid;
}