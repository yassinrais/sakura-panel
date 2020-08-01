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

	public function initialize(){
		parent::initialize();
		
		$this->page->set('base_route' , 'member/plugins');
		$this->page->set('title', 'Plugins Manager');
        $this->page->set('description','Install & Uninstall plugins from your website .');
		
        $this->view->dataTable = true;

        $this->checkViewsFiles('pluginsmanager');
        $this->gitrepo = getenv('GIT_REPO_ID');
	}

	public function checkViewsFiles($plugin_name)
	{
		$view_dir = $this->config->application->viewsDir . "/plugins/${plugin_name}/";
		
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
          				<div style='background: url($data[image])' class='plugin-logo br-5'></div>
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

          	if ($data['status'] != $this::DELETED)
          		$actions .= 
          		"<span title='Delete Row' data-action ='delete' data-id='$id' class='ml-1 btn btn-danger btn-circle btn-sm table-action-btn'><i class='fas fa-trash'></i></span>";
          	if ($data['status'] == $this::DELETED)
				$actions .= 
          		"<span title='Restore Row' data-action='restore' data-id='$id' class='ml-1 btn btn-info btn-circle btn-sm table-action-btn' ><i class='fas fa-trash-restore'></i></span>";

      		// $actions .= 
      		// 	"<a href='{$this->page->get('base_route')}/edit/$id' class='ml-1 btn btn-warning btn-circle btn-sm ' ><i class='fas fa-edit'></i></a>";


          	return $actions;
          })
          ->sendResponse();
        }
	}

	public function ajaxAllAction()
	{
		$this->view->disable();

		$plugins = $this->getFromGitHubPluginsSHA();

		$githubSha = $this->getFromGitHubPluginsSHA();

		if ($githubSha) {
			$githubShaLink = ($githubSha->commit->commit->tree->url ?? false);

			if ($githubShaLink) {
				$plugins = $this->getFromGitHubPlugins($githubShaLink);
				
				$this->response->setJsonContent([
					'status'=>'success',
					'data'=> $plugins
				]);
				return $this->response->send();
			}
		}

		$this->response->setJsonContent([
			'status'=>'error',
			'msg'=> (array) $this->flashSession->getMessages()
		]);

		return $this->response->send();
	}

	public function deleteAction()
	{
		$resp = $this::jsonStatus('error','Unknown error','danger');
		if ($this->request->isAjax()) {
			$id = (int) $this->request->get('id');

			$row = Plugins::findFirstById($id);

			if (!$row) {
				$resp = $this::jsonStatus('error','Unknown row id '.$id,'danger');
			}else{
				$row->status = $this::DELETED;

				if ($row->delete()) {
					$resp = $this::jsonStatus('success',"Row $id deleted successfully !",'success');
				}else{
					$resp = $this::jsonStatus('error',"Row $id deleted failed ! \n".implode("&", $row->getMessages()),'warning');
				}
			}

          	$this->response->setJsonContent($resp);

          	return $this->response;
        }
	}

	public function restoreAction()
	{
		$resp = $this::jsonStatus('error','Unknown error','danger');
		if ($this->request->isAjax()) {
			$id = (int) $this->request->get('id');

			$row = Plugins::findFirstById($id);

			if (!$row) {
				$resp = $this::jsonStatus('error','Unknown row id '.$id,'danger');
			}else{
				$row->status = $this::INACTIVE;

				if ($row->save()) {
					$resp = $this::jsonStatus('success',"Row $id restore successfully !",'success');
				}else{
					$resp = $this::jsonStatus('error',"Row $id restore failed ! \n".implode("&", $row->getMessages()),'warning');
				}
			}

          	$this->response->setJsonContent($resp);

          	return $this->response;
        }
	}


	/*********
	 ****** 
	 **** 
	 *
	 * Git Hub Plugins Helpers
	 *
	 **** 
	 ****** 
	*********/

	public function getFromGitHubPluginsSHA()
	{
		$url = "https://api.github.com/repos/{$this->gitrepo}/branches/master";

		$content = $this->getRequestContent($url);
		
		if ($content) {
			
			try {
				return json_decode($content);
			} catch (\Exception $e) {
				$this->flashSession->error($e->getMessage());
			}
		}

		return false;
	}

	public function getFromGitHubPlugins(string $url)
	{
		$plugins = [];	
		
		$content = $this->getRequestContent($url. "?recursive=1");

		if ($content) {
			
			try {
				$treeList = json_decode($content);

				foreach ($treeList->tree ?? [] as $object) {
					$type = $object->type;
					$path = $object->path;
					$path_array = explode("/", $path);
					if (count($path_array) == 2 && $path_array[1] == $this::PLUGIN_CONFIG_JSON) {
						$url = "https://api.github.com/repos/{$this->gitrepo}/contents/$path";

						$plugins[$path_array[0]] = json_decode($this->getConfigFileContent($url));
					}					
				}

				return $plugins;
			} catch (\Exception $e) {
				$this->flashSession->error($e->getMessage());
			}
		}

		$this->flashSession->error('Request to get plugins list was failed ! ');
	}


	public function getConfigFileContent(string $url)
	{
		$plugins = [];	
				
		$content = $this->getRequestContent($url);
		if ($content) {
			
			try {
				$treeList = json_decode($content);
				if (isset($treeList->download_url)) {
					return $this->getRequestContent($treeList->download_url);
				}

			} catch (\Exception $e) {
				$this->flashSession->error($e->getMessage());
			}
		}
		var_dump($url);
		$this->flashSession->error('Request to get plugin config file was failed ! ');
	}


	public function getRequestContent($url)
	{
		if (getenv('APP_DEBUG')) 
			$url = "http://me.spt.red/me.php?url=".urlencode($url);

		if ($this->cache->has(md5($url))) {
			$content = $this->cache->get(md5($url));
		}else{

			$curl = new \Curl\Curl();
			$curl->get($url);
			$content = $curl->rawResponse;

			if (!$content) 
				$content = file_get_contents($url);

			if ($content) {
				$this->cache->set(md5($url) , $content);
			}
		}


		return $content;
	}


}