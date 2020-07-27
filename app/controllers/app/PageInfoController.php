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

	private $menu = [];

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

	/**
	 * Get Panel Menu
	 */

	public function getMenu()
	{
		$access = $this->di->get('user')->role_name ?? "geusts";

		$this->menu = [];

		$allMenus = $this->config->menu;


		foreach ($allMenus as $category => $menu) {
			$this->menu[$category] = $this->menu[$category] ?? [];

			foreach ($menu['items'] ?? [] as $name => $info) {
				if (empty($info['access']) || in_array($access, explode("|", strtolower($info['access']))) || $info['access'] === "*") {
					$this->menu[$category]['items'] = array_merge_recursive(
						$this->menu[$category]['items'] ?? [],
						[
							$name => $info
						]
					);
				}
			}
		}


		return $this->menu;
	}
}