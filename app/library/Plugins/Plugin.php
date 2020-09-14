<?php 
declare(strict_types=1);

namespace SakuraPanel\Library\Plugins;


use SakuraPanel\Plugins\Pluginsmanager\Models\Plugins;

/**
 * Plugin
 */
class Plugin implements  \SakuraPanel\Library\SharedConstInterface
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
	protected $installation_cb;
	protected $plugin = null;

	const CACHE_LIFE_TIME = 60 * 60 * 5; // 5 hours

	protected $sqlFiles = ["install"=>[] , "update"=>[]];
	protected $di;

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

	/**
	 * Init plugin info by path
	 * @param $path : string
	 * @return $bool : bool
	 */
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
				(array) ($plugin->others ?? []),
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
	 * @param $order : mixed
	 * @return $this : Plugin
	 */
	public function addMenu(string $category , string $name , array $configs = [] , $order = true)
	{
		$category = strtolower($category);
		
		if (empty($this->menus[$category]))
			$this->menus[$category] = ['items'=>[] , 'order'=> INF];

		if (empty($this->menus[$category]['items'][$name])) 
			$this->menus[$category]['items'][$name]= [];

		$this->menus[$category]['items'] = array_merge_recursive( $this->menus[$category]['items'], [$name=>$configs]);
		if (is_numeric($order))
			$this->menus[$category]['order'] = $order;
		elseif($order)
			$this->menus[$category]['order'] = count($this->menus) + 1;

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
		$this->checkDb();
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
					if (is_int($acl_names)){
						// check if  array is associative or sequential
						$acl_names = $actions;
						$actions = ["*"];
					}
					
		            $urls = is_object($page->url) ? $page->url : (object) [$page->url];
		            $controller =  str_replace('[M]', 'Sakura\Controllers\Member', $page->controller );
		            $roles_alloweds = explode("|", $acl_names ?: "*");

					$actions = is_object($actions) && method_exists($actions , 'toArray') ? $actions->toArray() : $actions;

					$actions = is_array($actions) && !empty($actions) ? $actions : [$actions ?: '*'];

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
		
		if (!is_dir($view_dir) || getenv('DEV_MODE') == true) {
			@mkdir($view_dir , 0775 , true);
			
			$p = $this->getPluginSysPath($this->name)."views/";
			if (!is_dir($p)) return false;
			
			$scanned_dir = \SakuraPanel\Functions\_fullScanDirs($p.'*');


			foreach ($scanned_dir as $file) {


				$file_name = explode($p, $file)[1];
				
				$file_dest = $view_dir . $file_name;
				$file_source = $p . $file_name;
				
				if (is_dir($file_source) && !is_dir($file_dest))
					mkdir($file_dest, 0775 , true);

				if (is_file($file_source))
					copy($file_source, $file_dest);
			}
		}	
	}
	/**
	 * check if plugin exist
	 */
	public function checkDb()
	{
		if (class_exists(Plugins::class)) {
			$plugin = Plugins::findFirst([
				'name = ?0',	
				'bind'=>[ $this->name ],
				'cache'=>[
					'service'=>'cache',
					'lifetime'=>$this::CACHE_LIFE_TIME,
					'key'=>'plugin-'.sha1($this->name)
				]
			]);
				
			if (!$plugin) {
				$plugin = new Plugins();
				foreach (['name','author','version','title','image','description'] as $key) 
					if ($this->get($key)) 
						$plugin->{$key} = $this->{$key};

				if (!$plugin->save()){
					$this->di->getLogger()->warning("Plugin {$this->name} can not added to plugins table ! (".implode(",", $plugin->getMessages()).")");
					$this->di->getLogger()->warning(json_encode($plugin));
				}
			}

			$this->plugin = $plugin;

			$this->checkInstallation($plugin);
		}
	}

	/**
	 * method :: checkInstallation
	 */
	private function checkInstallation($plugin = null)
	{
		$plugin = $plugin != null ? $plugin: $this->plugin;
		if ($plugin == null || $plugin->installed == $this::ACTIVE) return;

		// install files sql
		$sqlToInstall = $this->sqlFiles['install'] ?? false;
 		$sqlStatus = [];

 		if ($sqlToInstall) {
 			// install files
 			foreach (is_array($sqlToInstall) ? $sqlToInstall : [$sqlToInstall] as $file) {
				$sqlStatus[] = (bool) $this->uploadSql($file);
 			}
 		}
 		if (in_array(false, $sqlStatus)) {
 			$this->di->get('flashSession')->error("[Plugin : SQL] Plugin `{$this->name}` Installation failed ! ");
			$this->di->get('logger')->error("[Plugin : SQL] Plugin `{$this->name}` Installation failed ! ");
			return;
 		}else{
			$this->di->get('logger')->info("[Plugin : SQL] Installation Plugin `{$this->name}` Total Sql Upload Success ! ".count($sqlStatus));
		}
		 
		$plugin->installed = $this::ACTIVE;
		
		$save  = $plugin->save();

		if (!$save)
			$this->di->get('logger')->error("[Plugin : Model] Plugin `{$this->name}` Save Installed failed ! ");

		return $save;
 	}


 	/**
 	 * uploadSql :: read & execute sql files 
 	 */
 	private function uploadSql($sqlFile)
 	{
		if (is_file($sqlFile)) {
			try {
				return $this->execSqlDump(file_get_contents($sqlFile));
			} catch (Exception $e) {
				$this->di->getLogger()->error($e->getMessage());
				return false;
			}
		}
		return false;
 	}


 	/**
 	 * Execute sql 
 	 */
 	private function execSqlDump($content)
 	{
 		if (!$this->di) throw new \Exception("Unknown di factory !");
 		
 		return $this->di->get('db')->execute($content);
 	}
 	/**
 	 * Delete Plugin
 	 */
 	public function delete()
 	{
 		// install files sql
		$sqlToDelete = $this->sqlFiles['delete'] ?? false;
 		$sqlStatus = [];

 		if ($sqlToDelete) {
 			// install files
 			foreach (is_array($sqlToDelete) ? $sqlToDelete : [$sqlToDelete] as $file) {
				$sqlStatus[] = (bool) $this->uploadSql($file);
 			}
 		}
 		if (in_array(false, $sqlStatus)) {
 			$this->di->get('flashSession')->error("[Plugin : SQL] Plugin `{$this->name}` Delete failed ! ");
 			return false;
 		}


 		return true;
 	}

 	/**
 	 * Update Plugin
 	 */
 	public function update()
	{
		$plugin = $this->plugin;
		if (!$plugin) {
			$this->di->get('flashSession')->error('Unknown plugin value in Plugin Object');
			return false;
		}

		// update files sql
		$sqlToUpdate = $this->sqlFiles['update'] ?? false;
 		$sqlStatus = [];

 		if ($sqlToUpdate) {
 			// update files
 			foreach (is_array($sqlToUpdate) ? $sqlToUpdate : [$sqlToUpdate] as $file) 
				$sqlStatus[] = (bool) $this->uploadSql($file);
 		}
 		if (in_array(false, $sqlStatus)) {
 			$this->di->get('flashSession')->error("[Plugin : SQL] Plugin `{$plugin->name}` Updated failed ! ");
 			return false;
 		}
		$plugin->setIp($this->di->get('request'));
		
		if(!$plugin->save()){
			$this->di->get('flashSession')->error("[Plugin : SQL] Plugin `{$plugin->name}` Updated failed ! ");
			return false;
		}
		 
 		return true;
 	}


 	/**
 	 * Add sql o sqlFiles
 	 */
 	public function addSql(array $files)
 	{
 		$this->sqlFiles = array_merge_recursive($this->sqlFiles, $files);
 	}

	/**
	 * Helpers
	 */
	public function getPluginViewPath()
	{
		return \SakuraPanel\Functions\_cleanPath($this->di->getConfig()->application->viewsDir . "/plugins/{$this->name}/");
	}
	public function getPluginSysPath()
	{
		return \SakuraPanel\Functions\_cleanPath($this->di->getConfig()->application->pluginsDir . "/{$this->name}/");
	}

	public function cleanPath(string $path)
	{
		return preg_replace('#/+#','/',$path);
	}
}