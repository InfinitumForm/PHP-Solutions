<?php

/*
 * FileSystemObject - Provides access to a computer's file system
 *
 * @author      Ivijan-Stefan Stipic <creativform@gmail.com>
 * @ver         1.0.0
 * @docs        https://docs.microsoft.com/en-us/office/vba/language/reference/user-interface-help/filesystemobject-object
 */
function list_drives($excludeDrives=array()) {
	$drives = array();
	
	// Fix error if $excludeDrives is not array - ignore it
	if(!is_array($excludeDrives)) $excludeDrives = array();
	
	if(!empty($excludeDrives))
	{
		$excludeDrives = array_map(function($value){
			if(strlen($value) > 1) return NULL;
			$value = strtoupper($value);
			$value = trim($value);
			return $value;
		}, $excludeDrives);
		$excludeDrives = array_filter($excludeDrives);
	}
	
	if(class_exists('COM'))
	{
		$fileSystemObject = new COM('Scripting.FileSystemObject'); 
		$driveDataObject = $fileSystemObject->Drives;
		
		foreach($driveDataObject as $object )
		{
			if(!($driveObject = $fileSystemObject->GetDrive($object))) continue;
			
			$driveLetter = strtoupper($driveObject->DriveLetter);
			
			// @driveObject->DriveType     array      0:"Unknown", 1:"Removable", 2:"Fixed", 3:"Network", 4:"CD-ROM", 5:"RAM Disk"
			if(in_array($driveObject->DriveType, array(2,3)) && !in_array($driveLetter, array_merge(array('C'), $excludeDrives), true)) {
				$drives[] = $driveLetter.':\\';
			}
		}
	}
	else if(is_callable('shell_exec') && false === stripos(ini_get('disable_functions'), 'shell_exec')) 
	{
		$output = shell_exec_utf8('fsutil fsinfo drives');
		if($output)
		{
			
			// Match drive letters and save in array
			preg_match_all('/([A-Z]:\\\)/Ux',((string)$output),$drives);
			
			if(!empty($drives) && isset($drives[0]))
			{
				
				// Filter only available drives
				$drives = array_filter($drives[0], function($i){
					$output = shell_exec_utf8('fsutil fsinfo drivetype ' . rtrim($i, '\\'));
					if(strpos(strtolower($output), 'pen') !== false 
						|| strpos(strtolower($output), 'usb') !== false
						|| strpos(strtolower($output), 'flash') !== false
						|| strpos(strtolower($output), 'emovable') !== false
						|| strpos(strtolower($output), 'ram') !== false
						|| strpos(strtolower($output), 'unknown') !== false
						|| strpos(strtolower($output), 'cd') !== false
						|| strpos(strtolower($output), 'c:') !== false) return false;
					return true;
				});

				sort($drives);
				
				$drives = array_map('trim',$drives);
				
				foreach($excludeDrives as $exclude){
					if (($key = array_search($exclude.':\\', $drives)) !== false) {
						unset($drives[$key]);
					}
				}
			}
		}
	}
	
	return $drives;
}