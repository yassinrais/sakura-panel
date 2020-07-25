<?php 

namespace SakuraPanel\Controllers\App;


/**
 * PageInfoController
 */
class PageInfoController extends \ControllerBase
{
	private $info = [
		'title'=>'',
		'desc'=>'',
		'keywords'=>'',
		'og:image'=>'',
	];


	/**
	 * get page info 
	 * @param $key
	 * @param $default = null
	 * @return $info
	 */
	public function get($key , $default = null)
	{
		return $this->info[$key] ?? $default;
	}

	/**
	 * set page info
	 * @param $key
	 * @param $val
	 */
	public function set($key , $val = null)
	{
		$this->info[$key] = $val;
	}
}