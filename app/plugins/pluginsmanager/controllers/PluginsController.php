<?php 

namespace SakuraPanel\Plugins\PluginsManager\Controllers;



use SakuraPanel\Controllers\Member\{
	MemberControllerBase
};

use SakuraPanel\Plugins\PluginsManager\Models\{
	Plugins
};

use SakuraPanel\Library\DataTables\DataTable;

use SakuraPanel\Plugins\PluginsManager\Forms\PluginsForm;

/**
 * PluginsController
 */
class PluginsController extends MemberControllerBase
{

	private $plugins_server = "https://raw.githubusercontent.com/yassinrais/sakura-plugins/master/";
	// private $plugins_server = "http://127.0.0.1:8080/";

	public function initialize(){
		parent::initialize();
		
		$this->page->set('base_route' , 'admin/plugins');
		$this->page->set('title', 'Plugins Manager');
        $this->page->set('description','Install & Uninstall plugins from your website .');
		
        $this->view->dataTable = true;
	}

	/**
	 * Plugins List : Page
	 */
	public function allAction()
	{
		$this->page->set('title', 'Plugins Manager');
		return $this->view->pick('plugins/pluginsmanager/index');
	}	


	/*********
	 ****** 
	 **** 
	 *
	 * Ajax : getAll, Install , delete , stop
	 *
	 **** 
	 ****** 
	*********/

	/**
	 * Get all plugins : ajax
	 */
	public function ajaxAllAction()
	{
		$this->ajax->error('The packages empty ! ');

		$plugins = $this->getPluginsList();

		if ($plugins !== null && count((array) $plugins)) {
			
			if (isset($plugins->plugins))
				foreach ($plugins->plugins as $name => $plugin) {
					$plugin->installed = false;
					$plugin->active = false;
					
					$db_plugin = Plugins::findFirstByName($name);
					if ($db_plugin !== null) {
						$plugin->installed = true;
						$plugin->active = $db_plugin->isActive();
					}
				}

			return $this->ajax->setData($plugins)->set('status','success')->sendResponse();
		}elseif (is_null($plugins)) {
			$this->ajax->clearMessages()->error('The server response was empty ! timeout*');
		}

		return $this->ajax->sendResponse();
	}

	/**
	 * Delete Plugin : ajax
	 */
	public function deleteAction()
	{
		if ($this->request->isAjax()) {
			// disable response array
			$this->ajax->disableArray();
	
			$id = (int) $this->request->get('id');
			$name = (string) $this->request->get('id');

			$row = Plugins::findFirstById($id) ?? Plugins::findFirstByName($name);

			if (!$row) {
				return $this->ajax->error('Unknown plugin  id/name '.$id)->sendResponse();
			}else{
				$plugin_name = $row->name;

				if (empty($plugin_name)) 
					return $this->ajax->error('This Plugin name is empty ! please be aware & check files !')->sendResponse();
			
				$row->status = $this::DELETED;

				$view_dir = $this->getPluginViewPath($plugin_name);
				$sys_dir = $this->getPluginSysPath($plugin_name);

				/**
				 * Delete Plugin (sql)
				 */
				$di_plugin = $this->plugins->get($row->name);
				// if ($di_plugin) 
					$di_plugin->delete();
				
				/**
				 * Delete plugin folders
				 */
				if (is_dir($view_dir)) 
					\SakuraPanel\Functions\_deleteDir($view_dir);
				if (is_dir($sys_dir)) 
					\SakuraPanel\Functions\_deleteDir($sys_dir);

				/**
				 * Delete row
				 */
				if ($row->delete()) {
					return $this->ajax->success("Plugin $plugin_name deleted successfully !",'success')->sendResponse();
				}else{
					return $this->ajax->error("Deleting Plugin $plugin_name was failed ! \n".implode("&", $row->getMessages()))->sendResponse();
				}
			}


          	return $this->ajax->error('Unknow error !')->sendResponse();
        }
	}
	/**
	 * Install Plugin : ajax
	 */
	public function installAction()
	{
		$this->ajax->disableArray();
		
		$resp = $this->ajax->error('Unknown Plugin !');
		$plugin = strtolower((string) $this->request->get('id'));

		$row = Plugins::findFirstByName($plugin);

		if ($row)
			return $this->ajax->error(strip_tags($row->title).': Plugin Already Installed')->sendResponse();

		if ( !preg_match('/^[a-z0-9]{2,31}$/', $plugin) )
			return $this->ajax->error("Plugin ".strip_tags($plugin)." Name is invalid !")->sendResponse();


		// get plugin info
		$plugin_info_json = $this->getRequestContent("{$this->plugins_server}{$plugin}/".self::PLUGIN_CONFIG_JSON);

		try {
			$plugin_info = json_decode($plugin_info_json);

			if (!empty($plugin_info->name)) {
				$this->ajax->setData($plugin_info);
				
				$zipFileUrl = "{$this->plugins_server}{$plugin}/{$plugin}.zip";

				if (!\SakuraPanel\Functions\_isUrlAZipFile($zipFileUrl)) {
					$this->ajax->error("Plugin ($zipFileUrl) file is not a zip file ");
				}else{
					$zipFileSavePath = $this->config->application->pluginsCacheDir . $plugin . ".zip";

					$download = \SakuraPanel\Functions\_downloadZipFile($zipFileUrl , $zipFileSavePath);

					if (!$download) 
						return $this->ajax->error("Download plugin failed ! $zipFileUrl")->sendResponse();
					

					$unzip = $this->unzipPlugin($plugin_info, $zipFileSavePath);
					if (!$unzip) 
						return $this->ajax->error('Unzipping plugin failed ! ')->sendResponse();

					$row = new Plugins();
					foreach (['image','name','title','description','author','version','tags'] as $key) {
						if (isset($plugin_info->$key)) 
							$row->$key = $plugin_info->$key;
					}
					$row->status = $this::ACTIVE;
					$row->setIp($this->request);

					if ($row->save()) {
						$this->ajax->clearMessages()->success("Plugin {$plugin_info->name} installed successfully !");
					}else{
						foreach ($row->getMessages() as $msg)
							$this->ajax->error($msg);
					}
				}

				return $this->ajax->sendResponse();
			}
		} catch (Exception $e) {
			return $this->ajax->error('Invalid Plugins info\'s '. $e->getMessage())->sendResponse();
		}

      	return $this->ajax->sendResponse();
	}

	/**
	 * Update Plugin : ajax
	 */
	public function updateAction()
	{
		$this->ajax->disableArray();
		
		$resp = $this->ajax->error('Unknown Plugin !');
		$plugin = strtolower((string) $this->request->get('id'));

		$row = Plugins::findFirstByName($plugin);

		if (!$row)
			return $this->ajax->error(strip_tags($row->title).': Plugin undefined in database ')->sendResponse();

		if ( !preg_match('/^[a-z0-9]{2,31}$/', $plugin) )
			return $this->ajax->error("Plugin ".strip_tags($plugin)." Name is invalid !")->sendResponse();


		// get plugin info
		$plugin_info_json = $this->getRequestContent("{$this->plugins_server}{$plugin}/".self::PLUGIN_CONFIG_JSON);

		try {
			$plugin_info = json_decode($plugin_info_json);

			if (!empty($plugin_info->name)) {
				$this->ajax->setData($plugin_info);
				
				$zipFileUrl = "{$this->plugins_server}{$plugin}/{$plugin}.zip";

				if (!\SakuraPanel\Functions\_isUrlAZipFile($zipFileUrl)) {
					$this->ajax->error("Plugin ($zipFileUrl) file is not a zip file ");
				}else{
					$zipFileSavePath = $this->config->application->pluginsCacheDir . $plugin . ".zip";

					$download = \SakuraPanel\Functions\_downloadZipFile($zipFileUrl , $zipFileSavePath);

					if (!$download) 
						return $this->ajax->error("Download plugin failed ! $zipFileUrl")->sendResponse();
					

					$unzip = $this->unzipPlugin($plugin_info, $zipFileSavePath);
					if (!$unzip) 
						return $this->ajax->error('Unzipping plugin failed ! ')->sendResponse();

					// update plugin info
					foreach (['image','name','title','description','author','version','tags'] as $key) 
						if (isset($plugin_info->$key)) 
							$row->$key = $plugin_info->$key;


					$row->status = $this::ACTIVE;
					$row->setIp($this->request);

					if ($row->save()) {
						return $this->response->redirect($this->page->get('base_route').'/updatePlugin/'.urlencode($row->name));
					}else{
						foreach ($row->getMessages() as $msg)
							$this->ajax->error($msg);
					}
				}

				return $this->ajax->sendResponse();
			}
		} catch (Exception $e) {
			return $this->ajax->error('Invalid Plugins info\'s '. $e->getMessage())->sendResponse();
		}

      	return $this->ajax->sendResponse();
	}

	public function updatePluginAction(string $plugin_name = null)
	{
		$plugin_name = strtolower(strip_tags(urldecode($plugin_name)));
		$p = $this->plugins->get($plugin_name);

		if (!$p)
			return $this->ajax->error($plugin_name .' : Unknown Plugin name')->sendResponse();
		
		if ($p->update())
			return $this->ajax->success("Plugin {$p->get('name')} updated successfully !")->sendResponse();
		
		return $this->ajax->error(implode(" , ", $this->flashSession->getMessages()))->sendResponse();
	}

	/*********
	 ****** 
	 **** 
	 *
	 * Plugins Hosting : grabber
	 *
	 **** 
	 ****** 
	*********/

	public function getRequestContent($url)
	{
	 
		$curl = new \Curl\Curl();
		$curl->get($url);
		$content = $curl->rawResponse;
		$curl->setTimeout(1);
		if (!$content) 
			$content = file_get_contents($url);

		return $content;
	}

	public function getPluginsList()
	{
		$x=$this->getRequestContent($this->plugins_server . "/plugins.json");
		return json_decode($x);
	}





	/*********
	 ****** 
	 **** 
	 *
	 * Plugins Helpers : getPath/unzip Plugin
	 *
	 **** 
	 ****** 
	*********/
	public function getPluginViewPath(string $name)
	{
		return \SakuraPanel\Functions\_cleanPath($this->config->application->viewsDir . "/plugins/{$name}/");
	}
	public function getPluginSysPath(string $name)
	{
		return \SakuraPanel\Functions\_cleanPath($this->config->application->pluginsDir . "/{$name}/");
	}

	public function unzipPlugin($plugin , $path)
	{
		$zip = new \ZipArchive;
		$res = $zip->open($path);
		if ($res === TRUE) {
		  	$zip->extractTo($this->getPluginSysPath($plugin->name));
		  	$zip->close();

		  	return true;
		} else {
			return false;
		}

	}
}