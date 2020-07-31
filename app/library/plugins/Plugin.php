<?php 

namespace SakuraPanel\Library\Plugins;

/**
 * Plugin
 */
class Plugin 
{
	protected $title = "Plugin";
	protected $name = "plugin";
	protected $version = "1.0.0";
	protected $author = "Yassine Rais";
	protected $require  = [];
	protected $status = 1;
	protected $access = "*";
	protected $routes = [];
	protected $menus = [];

	/**
	 * Init Plugin configs
	 * @param $title : string
	 * @param $name : string
	 * @param $version : string
	 * @param $author : string
	 * @param $configs : array
	 */
	public function initPlugin(string $title , string $name , string $version ="1.0.0", string $author = "Anonymous" , array $others = [])
	{
		$this->name = $name;
		$this->title = $title;
		$this->version = $version;
		$this->author = $author;

		foreach ($others as $key => $value)
			$this->set($key , $value);

	}

	/**
	 * Get an attribute value (i dont use method magic _get)
	 * @param $key : string 
	 * @return $value : mixed
	 */
	public function get(string $key)
	{
		return $this->{$key} ?? null;
	}

	/**
	 * Add plugin to rotues list
	 * @param $category : string
	 * @param $name : string
	 * @param $configs : array
	 * @return $this : Plugin
	 */
	public function addRoute(string $category , string $name , array $configs = [])
	{
		if (empty($this->routes[$category]))
			$this->routes[$category] = [];
		$this->routes[$category] = array_merge_recursive((array)$this->routes[$category] , [$name=>$configs]);
		
		return $this;
	}

	/**
	 * Add plugin to rotues list
	 * @param $category : string
	 * @param $name : string
	 * @param $configs : array
	 * @param $order : int
	 * @return $this : Plugin
	 */
	public function addMenu(string $category , string $name , array $configs = [] , int $order = 555)
	{
		if (empty($this->menus[$category]))
			$this->menus[$category] = ['items'=>[]];

		if (empty($this->menus[$category]['items'][$name])) 
			$this->menus[$category]['items'][$name]= [];

		$this->menus[$category]['order'] = $order;
		$this->menus[$category]['items'] = array_merge_recursive( $this->menus[$category]['items'], [$name=>$configs]);
		
		return $this;
	}

	/**
	 * Load : Plugin Routes / Menu
	 */
	public function load($di)
	{
		$this->loadRoutes($di);
		$this->loadMenu($di);
	}

	/**
	 * load plugin routes
	 */
	public function loadRoutes($di)
	{
		$router = $di->get('router');
		$acl = $di->getAcl();
		foreach ((object) $this->routes as $prefix => $rgroups) {
		    foreach ($rgroups as $name => $page) {
		        $page = (object) $page;
		        foreach ($page->url as $url) {
		        	$routeConfig = [
		                    'controller' => $page->controller,
		                    'action'     => (!empty($page->action)) ? $page->action : 'index',
		                    'params'     => (!empty($page->params)) ? $page->params : null,
		                ];
		            $router->add(
		                str_replace("@", $name,  str_replace("#", $prefix, $url)),
		                $routeConfig
		            );
		        }
		        $acl_item = $page->access;
		        foreach ($acl_item as $acl_names => $actions) {

		            $urls = is_object($page->url) ? $page->url : (object) [$page->url];
		            $controller =  str_replace('[M]', 'Sakura\Controllers\Member', $page->controller );
		            $roles_alloweds = explode("|", $acl_names ?: "*");

		            $actions = !is_array($actions) && !empty($actions->toArray()) ? $actions->toArray() : ['*'];
		    
		            $acl->addComponent(
		                $controller,
		                $actions
		            );

		            foreach ($roles_alloweds as $name)
		                $acl->allow($name , $controller , $actions);
		        }
		    }
		}

	}


	/**
	 * load plugin menu
	 */
	public function loadMenu($di)
	{
		$di->getConfig()->menu = array_merge_recursive( $di->getConfig()->menu->toArray() , (array) $this->menus);
	}
	 
}