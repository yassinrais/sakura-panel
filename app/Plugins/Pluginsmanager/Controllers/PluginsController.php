<?php 

namespace Sakura\Plugins\Pluginsmanager\Controllers;



use Sakura\Controllers\Member\{
	MemberControllerBase
};

use Sakura\Plugins\Pluginsmanager\Models\{
	Plugins
};

use Sakura\Library\Datatables\DataTable;

use Sakura\Plugins\Pluginsmanager\Forms\PluginsForm;

/**
 * PluginsController
 */
class PluginsController extends MemberControllerBase
{

	private $plugins_server =  "https://raw.githubusercontent.com/yassinrais/sakura-plugins/master/";

	public function initialize(){
		parent::initialize();
		
		$this->page->set('title', 'Plugins Manager');
		$this->page->set('base_route' , 'admin/plugins');
        $this->page->set('description','Install & Uninstall plugins from your website .');
		
		$this->view->dataTable = true;
		
		if (getenv("PLUGINS_MANAGER_REPO"))
			$this->plugins_server = getenv("PLUGINS_MANAGER_REPO");

	}

	/**
	 * Plugins List : Page
	 */
	public function allAction()
	{
		$this->page->set('title', 'Plugins Manager');
		return $this->view->pick('plugins/Pluginsmanager/index');
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
				$di_plugin = $this->getDi()->get('plugins')->get($row->name);
				if ($di_plugin) 
					$di_plugin->delete();
				
				/**
				 * Delete plugin folders
				 */
				if (is_dir($sys_dir)) 
					\Sakura\Helpers\Functions::_deleteDir($sys_dir);
				if (is_dir($view_dir)) 
					\Sakura\Helpers\Functions::_deleteDir($view_dir);

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
		
		$plugin = (string) $this->request->get('id');

		$row = Plugins::findFirstByName($plugin);

		$resp = $this->ajax->error("{$plugin} : Unknown Plugin !");
		if ($row)
			return $this->ajax->error(strip_tags($row->title).': Plugin Already Installed')->sendResponse();

		if ( !preg_match('/^[a-zA-Z0-9]{2,31}$/', $plugin) )
			return $this->ajax->error("Plugin ".strip_tags($plugin)." Name is invalid !")->sendResponse();


		$plugins_path_url = "{$this->plugins_server}{$plugin}/".self::PLUGIN_CONFIG_JSON."?i=".rand();
		// get plugin info
		$plugin_info_json = $this->getRequestContent($plugins_path_url);

		try {
			$plugin_info = json_decode($plugin_info_json);

			if (!empty($plugin_info->name)) {
				$this->ajax->setData(['json'=>$plugin_info,'path'=>$plugins_path_url]);
				
				$zipFileUrl = $plugin_info->zip ?? null;
				if(!$zipFileUrl)
					return $this->ajax->error('Unknown Plugin zip path file !')->sendResponse();

				$zipFileUrl = "{$this->plugins_server}{$zipFileUrl}";

				if (!\Sakura\Helpers\Functions::_isUrlAZipFile($zipFileUrl)) {
					$this->ajax->error("Plugin ($zipFileUrl) file is not a zip file ");
				}else{
					$zipFileSavePath = $this->config->application->cache->plugins . $plugin . ".zip";

					$download = \Sakura\Helpers\Functions::_downloadZipFile($zipFileUrl , $zipFileSavePath);

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
	 * Plugin : ajax
	 */
	public function updateAction()
	{
		$this->ajax->disableArray();
		
		$plugin = (string) $this->request->get('id');

		$row = Plugins::findFirstByName($plugin);
		$resp = $this->ajax->error("{$plugin} : Unknown Plugin !");

		if (!$row)
			return $this->ajax->error(strip_tags($row->title).': Plugin undefined in database ')->sendResponse();

		if ( !preg_match('/^[a-zA-Z0-9]{2,31}$/', $plugin) )
			return $this->ajax->error("Plugin ".strip_tags($plugin)." Name is invalid !")->sendResponse();


		$plugins_path_url = "{$this->plugins_server}{$plugin}/".self::PLUGIN_CONFIG_JSON."?i=".rand();
		// get plugin info
		$plugin_info_json = $this->getRequestContent($plugins_path_url);

		try {
			$plugin_info = json_decode($plugin_info_json);

		
			if (!empty($plugin_info->name)) {
				$this->ajax->setData(['json'=>$plugin_info,'path'=>$plugins_path_url]);
				
				$zipFileUrl = $plugin_info->zip ?? null;
				if(!$zipFileUrl)
					return $this->ajax->error('Unknown Plugin zip path file !')->sendResponse();

				$zipFileUrl = "{$this->plugins_server}{$zipFileUrl}";

				if (!\Sakura\Helpers\Functions::_isUrlAZipFile($zipFileUrl)) {
					$this->ajax->error("Plugin ($zipFileUrl) file is not a zip file ");
				}else{
					$zipFileSavePath = $this->config->application->cache->plugins . $plugin . ".zip";

					$download = \Sakura\Helpers\Functions::_downloadZipFile($zipFileUrl , $zipFileSavePath);

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
		$this->ajax->disableArray();

		$plugin_name = strip_tags(urldecode($plugin_name));
		$p = $this->plugins->get($plugin_name);

		if (!$p)
			return $this->ajax->error($plugin_name .' : Unknown Plugin name')->sendResponse();
		
		if ($p->update())
			return $this->ajax->success("Plugin {$p->get('name')} updated successfully !")->sendResponse();
		
		return $this->ajax->error("Erro Updating Row Plugin :". implode(" , ", $this->flashSession->getMessages()))->sendResponse();
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
	 
		$content = file_get_contents($url);
		if (!$content) 
		{
			$curl = new \Curl\Curl();
			$curl->get($url);
			$curl->setTimeout(5);
			$content = $curl->rawResponse;
		}

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
		return \Sakura\Helpers\Functions::_cleanPath($this->config->application->viewsDir . "/plugins/{$name}/");
	}
	public function getPluginSysPath(string $name)
	{
		return \Sakura\Helpers\Functions::_cleanPath($this->config->application->pluginsDir . "/{$name}/");
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