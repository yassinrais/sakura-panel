<?php 
namespace SakuraPanel\Functions;

/**
 * Delete dir : danger dont please put an empty path : else you will remove your root 
 */
if (!function_exists('_deleteDir')) 
{
	function _deleteDir(string $path) {
	    if (empty($path)) { 
	        return false;
	    }
	    return is_file($path) ?
	            @unlink($path) :
	            array_map(__FUNCTION__, glob($path.'/*')) == @rmdir($path);
	}

}

/**
 * Check if an url file is a zip by reading first 4 bytes
 */
if (!function_exists('_isUrlAZipFile')) {
	function _isUrlAZipFile(string $url)
	{
	    $ch = curl_init($url);

	    $headers = array(
	        'Range: bytes=0-4',
	        'Connection: close',
	    );

	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2450.0 Iron/46.0.2450.0');
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	    curl_setopt($ch, CURLOPT_VERBOSE, 0); // set to 1 to debug

	    $header = '';

	    // write function that receives data from the response
	    // aborts the transfer after reading 4 bytes of data
	    curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($curl, $data) use(&$header) {
	        $header .= $data;

	        if (strlen($header) < 4) return strlen($data);

	        return 0; // abort transfer
	    });

	    $result = curl_exec($ch);
	    $info   = curl_getinfo($ch);

	    // check for the zip magic header, return true if match, false otherwise
	    return preg_match('/^PK(?:\x03\x04|\x05\x06|0x07\x08)/', $header);
	}
}

/**
 * Download a file zip by usin two methods : curl or file_get_contents
 */
if (!function_exists('_downloadZipFile')) {
	function _downloadZipFile(string $url, string $filepath){
		$fp = fopen($filepath, 'w+');
		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
		//curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_exec($ch);

		curl_close($ch);
		fclose($fp);

		$r =(filesize($filepath) > 0)? true : false;

		if (!$r)
			$r = file_put_contents($filepath, file_get_contents($url));
		return $r;
	}
}

/**
 * Scan & Sroted Dirs/Files by order racine to sub files
 */
if (!function_exists('_sortDirFiles')) {
	function _sortDirFiles($dir)
	{
	        $sortedData = array();
	        foreach(scandir($dir) as $file)
	        {
	                if(is_file($dir.'/'.$file))
	                        array_push($sortedData, $file);
	                else
	                        array_unshift($sortedData, $file);
	        }
	        return $sortedData;
	}
}

/**
 * Full Scan dir by pattern & $flags
 */
if ( ! function_exists('_fullScanDirs'))
{
    // Does not support flag GLOB_BRACE        
   function _fullScanDirs($pattern, $flags = 0)
   {
     $files = glob($pattern, $flags);
     foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir)
     {
       $files = array_merge($files, _fullScanDirs($dir.'/'.basename($pattern), $flags));
     }
     return $files;
   }
}

/**
 * Remove Duplicate slashes in a path
 */
if (!function_exists('_cleanPath')) {
	function _cleanPath(string $path){
		return preg_replace('#/+#','/',$path);
	}
}