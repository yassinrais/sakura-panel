<?php 
namespace SakuraPanel\Functions;

/**
 * Delete dir : danger dont please put an empty path : else you will remove your root 
 */

if (!function_exists('_deleteDir')) 
{
	function _deleteDir($path) {
	    if (empty($path)) { 
	        return false;
	    }
	    return is_file($path) ?
	            @unlink($path) :
	            array_map(__FUNCTION__, glob($path.'/*')) == @rmdir($path);
	}

}