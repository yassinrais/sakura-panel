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

		
		foreach ($this->config->menu->toArray() as $category => $menu) {
			$this->menu[$category] = $this->menu[$category] ?? ['items'=>[] , 'order'=>$menu['order'] ?? 999 ];

			foreach ($menu['items'] ?? [] as $name => $info) {
				if (empty($info['access']) || (!is_array($info['access'])) && in_array($access, explode("|", strtolower($info['access']))) || $info['access'] === "*") {
					if (!empty($info['sub'])) {
						$ninfo = [];
						foreach ($info['sub'] as $sub) {
							if (empty($sub['access']) || in_array($access, explode("|", strtolower($sub['access']))) || $sub['access'] === "*") 
								$ninfo[] = $sub;
						}
						$info['sub'] = $ninfo;
					}
					$this->menu[$category]['items'] = array_merge_recursive(
						$this->menu[$category]['items'] ?? [],
						[
							$name => $info
						]
					);
				}
			}
			if (!empty($this->menu[$category]['order']) && is_array($this->menu[$category]['order'])) 
					$this->menu[$category]['order'] = end($this->menu[$category]['order']);
			else 
				$this->menu[$category]['order'] = $this->menu[$category]['order'] ?? $order;
		}
		$this->menu = $this->sortedMenu($this->menu , "order");

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