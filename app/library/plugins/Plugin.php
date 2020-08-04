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

	private $di;

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

		$this->status = true;
	}

	public function initPluginByJson(string $path ="")
	{
		$this->status = 0;

		if (!is_file($path)) 
			return false;
		try{
			$plugin_str = file_get_contents($path);
			$plugin = json_decode($plugin_str);

			return $this->initPlugin(
				$plugin->title ?? 'Unammed',
				$plugin->name ?? 'unammed',
				$plugin->version ?? '1.0.0',
				$plugin->author ?? 'Anonymous',
				(array) $plugin->others ?? [],
			);			

		}catch(\Exception $e){
		}

		return (json_last_error() == JSON_ERROR_NONE);
	}
	/**
	 * Get an attribute value (i dont use method magic __get)
	 * @param $key : string 
	 * @return $value : mixed
	 */
	public function get(string $key)
	{
		return $this->{$key} ?? null;
	}
	/**
	 * set an attribute value (i dont use method magic __set)
	 * @param $key : string 
	 * @param $value : mixed
	 */
	public function set(string $key , $value = null)
	{
		return $this->{$key}  = $value;
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
		$category = strtolower($category);

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
		$category = strtolower($category);
		
		if (empty($this->menus[$category]))
			$this->menus[$category] = ['items'=>[] , 'order'=>0];

		if (empty($this->menus[$category]['items'][$name])) 
			$this->menus[$category]['items'][$name]= [];

		$this->menus[$category]['items'] = array_merge_recursive( $this->menus[$category]['items'], [$name=>$configs]);
		// $this->menus[$category]['order'] = $order;
		
		return $this;
	}

	/**
	 * Load : Plugin Routes / Menu
	 */
	public function load($di)
	{
		$this->di = $di;
		$this->loadViews();
		$this->loadRoutes();
		$this->loadMenu();
	}

	/**
	 * load plugin routes
	 */
	private function loadRoutes()
	{
		$di = $this->di;
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
		        $acl_item = is_array($page->access) ? $page->access : [$page->access];
		        foreach ($acl_item as $acl_names => $actions) {

		            $urls = is_object($page->url) ? $page->url : (object) [$page->url];
		            $controller =  str_replace('[M]', 'Sakura\Controllers\Member', $page->controller );
		            $roles_alloweds = explode("|", $acl_names ?: "*");

		            $actions = is_object($actions) && !empty($actions->toArray()) ? $actions->toArray() : ['*'];
		    
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
	private function loadMenu()
	{
		$di = $this->di;

		$di->getConfig()->menu = array_merge_recursive( $di->getConfig()->menu->toArray() , (array) $this->menus);
	}
	

	/**
     * check views exist or not  
     */	 
	private function loadViews()
	{

		$view_dir = $this->getPluginViewPath($this->name);
		
		if (!is_dir($view_dir)) {
			mkdir($view_dir , 0775 , true);
			
			$p = $this->getPluginSysPath($this->name)."views/";

			foreach (scandir($p) as $file_name) {
				if (!in_array($file_name, ['.','..'])){ // if its not a . .. path : copy files
					copy($p . $file_name, $view_dir . $file_name);
				}
			}
		}	
	}

	/**
	 * Helpers
	 */
	public function getPluginViewPath()
	{
		return $this::cleanPath($this->di->getConfig()->application->viewsDir . "/plugins/{$this->name}/");
	}
	public function getPluginSysPath()
	{
		return $this::cleanPath($this->di->getConfig()->application->pluginsDir . "/{$this->name}/");
	}

	public function cleanPath(string $path)
	{
		return preg_replace('#/+#','/',$path);
	}
}