<?php 

namespace SakuraPanel\Library;


/**
 * PageInfoManager
 */
class PageInfoManager extends \ControllerBase
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

		$allMenus = $this->sortedMenu($this->config->menu->toArray() , "order");

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


	public function sortedMenu(array $array,string $on, $order=SORT_ASC)
	{
	    $new_array = array();
	    $sortable_array = array();

	    if (count($array) > 0) {
	        foreach ($array as $k => $v) {
	            if (is_array($v)) {
	                foreach ($v as $k2 => $v2) {
	                    if ($k2 == $on) {
	                        $sortable_array[$k] = $v2;
	                    }
	                }
	            } else {
	                $sortable_array[$k] = $v;
	            }
	        }

	        switch ($order) {
	            case SORT_ASC:
	                asort($sortable_array);
	            break;
	            case SORT_DESC:
	                arsort($sortable_array);
	            break;
	        }

	        foreach ($sortable_array as $k => $v) {
	            $new_array[$k] = $array[$k];
	        }
	    }

	    return $new_array;
	}
}