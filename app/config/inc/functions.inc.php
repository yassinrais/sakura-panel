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
	    curl_setopt($ch, CURLOPT_STDERR, fopen('php://output', 'r'));

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
	    return preg_match('/^PK(?:\x03\x04|\x05\x06|0x07\x08)/', $header) ? $result : false;
	}
}