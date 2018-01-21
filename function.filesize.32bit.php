<?php

/**
 * Get real file size on 32bit operating system or 32bit PHP
 * @author Ivijan-Stefan Stipic <creativform@gmail.com>
 * @version 1.0.0 BETA
 *
 * @require /class.os.php
 * @return string numeric value of the file size
**/
function get_filesize($filename)
{
  $buffer = '';
  $cnt    = 0;
  $chunk_size = 1024*1024;
  $filename = str_replace(array("\\",'//'),"/",$filename);

  if(file_exists($filename)) :
    // First try get via SHELL if is active
    if(OS::is_win())
    {
      if(function_exists('shell_exec'))
      {
        $size = shell_exec('for %F in ("'.$filename.'") do @echo %~zF');
        if(!empty($size))
        {
          $cnt = trim($size);
        }
      }
    }
    else
    {
      if(function_exists('shell_exec'))
      {
        $size = shell_exec('stat -c %s '.$filename);
        if(!empty($size))
        {
          $cnt = trim($size);
        }
      }
    }

    // SHELL fail in most cases, do PHP magic
    if($cnt === 0)
    {
      if(OS::is_php64()!==false && OS::is_os64()!==false)
      {
        // 64 bit
        $cnt = @filesize($filename);
      }
      else
      {
        // 32 bit
        $handle = fopen($filename, 'rb');

        if ($handle === false) {
          return false;
        }

        while (!feof($handle)) {
          $buffer = fread($handle, $chunk_size);
          if ($buffer!==false) {
            $cnt += strlen($buffer);
          }
        }

        fclose($handle);
      }
    }
  endif;
  return $cnt;
}
