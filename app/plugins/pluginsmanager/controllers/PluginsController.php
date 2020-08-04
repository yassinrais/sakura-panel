<?php 

namespace SakuraPanel\Plugins\PluginsManager\Controllers;


use SakuraPanel\Controllers\Member\{
	MemberControllerBase
};

use SakuraPanel\Plugins\PluginsManager\Models\{
	Plugins
};

use SakuraPanel\Library\DataTable\DataTable;

use SakuraPanel\Plugins\PluginsManager\Forms\PluginsForm;

/**
 * PluginsController
 */
class PluginsController extends MemberControllerBase
{

	private $plugins_server = "https://raw.githubusercontent.com/yassinrais/sakura-plugins/master/";

	public function initialize(){
		parent::initialize();
		
		$this->page->set('base_route' , 'member/plugins');
		$this->page->set('title', 'Plugins Manager');
        $this->page->set('description','Install & Uninstall plugins from your website .');
		
        $this->view->dataTable = true;

        $this->checkViewsFiles('pluginsmanager');
	}

	public function checkViewsFiles($plugin_name)
	{
		$view_dir = $this->getPluginViewPath($plugin_name);
		
		if (!is_dir($view_dir)) {
			mkdir($view_dir , 0775 , true);
			
			$p = dirname(__FILE__)."/../views/";

			foreach (scandir($p) as $file_name) 
				copy($p . $file_name, $view_dir . $file_name);
		}	
	}

	public function installedAction()
	{
		$this->page->set('title', 'Installed Plugins');
		return $this->view->pick('plugins/pluginsmanager/installed');
	}	
	public function allAction()
	{
		$this->page->set('title', 'All Free Plugins');
		return $this->view->pick('plugins/pluginsmanager/all');
	}	

	public function createAction()
	{
		$row = new Plugins();
		$form = new PluginsForm($row);


		if ($this->request->isPost()) {
			$row->user_id = (int) $this->di->get('user')->id;
			$row->setIp($this->request);


            if (false === $form->isValid($_POST)) {
			    $messages = $form->getMessages();

			    foreach ($messages as $message)
			        $this->flashSession->warning((string) $message);
			}else{
				$form->bind($this->request->getPost(), $row);

				if ($row->save()) {
					$this->flashSession->success('New Row created successfully ! ');
 					
 					$form->clear();
				}else{
				    foreach ($row->getMessages() as $message)
				        $this->flashSession->warning((string) $message);
				}

			}
		}

		$this->view->form = $form;
		// $this->view->form->bind($row->toArray() , $row);

		return $this->view->pick('plugins/pluginsmanager/form');
	}
	public function ajaxAction()
	{
		if ($this->request->isAjax()) {
          $builder = $this->modelsManager->createBuilder()
                          ->columns('id, name, title, [description], image , author, version , status')
                          ->from(Plugins::class);

          $dataTables = new DataTable();
          $dataTables->setIngoreUpperCase(true);
          
          $dataTables->fromBuilder($builder)
          ->addCustomColumn('c_status' , function ($key , $data) {
          	$s = Plugins::getStatusById($data['status']);
          	return "<span class='btn btn-$s->color btn-icon-split btn-sm p-0'>
				<span class='icon text-white-50'>
				  <i class='fas fa-$s->icon' style='width:20px'></i>
				</span>
				<span class='text'>$s->title</span>
			</span>";
          })
          ->addCustomColumn('c_plugin' , function ($key , $data)
          {
          	return "
          		<div class=row>
          			<div class='col-md-5 col-xs-12' >
          				<div style='background-image: url($data[image])' class='plugin-logo br-5'></div>
          			</div>
          			<div class='col-md-7 col-xs-12'>
          				<p>".strip_tags($data['name'])."</p>
          				<h5>".strip_tags($data['title'])."</h5>
          			</div>
          		</div>
          	";
          })
          ->addCustomColumn('c_actions' , function ($key , $data) {
          	$id = $data['id'];
          	$actions = "";

       		$actions .= 
          		"<span title='Delete Row' data-path='/{$this->page->get('base_route')}' data-action='delete' data-id='$id' class='ml-1 btn btn-danger btn-circle btn-sm table-action-btn'><i class='fas fa-trash'></i></span>"; 

      		// $actions .= 
      		// 	"<a href='{$this->page->get('base_route')}/edit/$id' class='ml-1 btn btn-warning btn-circle btn-sm ' ><i class='fas fa-edit'></i></a>";


          	return $actions;
          })
          ->sendResponse();
        }
	}


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


	/*********
	 ****** 
	 **** 
	 *
	 * Plugins : Install , delete , stop
	 *
	 **** 
	 ****** 
	*********/

	public function installAction()
	{
		$this->ajax->disableArray();
		
		$resp = $this->ajax->error('Unknown Plugin !');
		$plugin = strtolower((string) $this->request->get('id'));

		$row = Plugins::findFirstByName($plugin);

		if ($row)
			return $this->ajax->error(strip_tags($row->title).': Plugin Already Installed')->sendResponse();

		if ( !preg_match('/^[a-z0-9]{5,31}$/', $plugin) )
			return $this->ajax->error("Plugin ".strip_tags($plugin)."Name is invalid !")->sendResponse();


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
		}

      	return $this->ajax->sendResponse();
	}

	/*********
	 ****** 
	 **** 
	 *
	 * Host Plugins : grabber
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





	/**
	 * Helpers
	 */
	public function getPluginViewPath(string $name)
	{
		return $this::cleanPath($this->config->application->viewsDir . "/plugins/{$name}/");
	}
	public function getPluginSysPath(string $name)
	{
		return $this::cleanPath($this->config->application->pluginsDir . "/{$name}/");
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