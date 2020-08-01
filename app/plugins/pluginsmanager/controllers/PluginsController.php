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

	private $plugins_server = "http://localhost:8080/";

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
		$this->response->setJsonContent([
			'status'=>'error',
			'msg'=>'The package list is empty ! '
		]);

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

			$this->response->setJsonContent([
				'status'=>'success',
				'data'=> $plugins
			]);
		}

		return $this->response;
	}



	public function deletePluginAction($name)
	{
		$resp = $this::jsonStatus('error','Unknown error','danger');
		if ($this->request->isAjax()) {
			$id = (int) $this->request->get('id');

			$row = Plugins::findFirstByName($name);

			if (!$row) {
				$resp = $this::jsonStatus('error','Unknown row id '.$id,'danger');
			}else{
				$row->status = $this::DELETED;

				$view_dir = $this->config->application->viewsDir . "/plugins/${row->name}/";

				if (is_dir($view_dir) && !empty($row->name)) {
					exit('try to delete $view_dir');
					\SakuraPanel\Functions\_deleteDir($view_dir);
				}
				// if ($row->delete()) {
					$resp = $this::jsonStatus('success',"Row $id deleted successfully !",'success');
				// }else{
				// 	$resp = $this::jsonStatus('error',"Row $id deleted failed ! \n".implode("&", $row->getMessages()),'warning');
				// }
			}

          	$this->response->setJsonContent($resp);

          	return $this->response;
        }
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
	 * Host Plugins : grabber
	 *
	 **** 
	 ****** 
	*********/

	public function getRequestContent($url)
	{
		// if (getenv('APP_DEBUG')) 
			// $url = ($url);

		// if ($this->cache->has(md5($url))) {
		// 	$content = $this->cache->get(md5($url));
		// }else{

			$curl = new \Curl\Curl();
			$curl->get($url);
			$content = $curl->rawResponse;

			if (!$content) 
				$content = file_get_contents($url);

		// 	if ($content) {
		// 		$this->cache->set(md5($url) , $content);
		// 	}
		// }


		return $content;
	}

	public function getPluginsList()
	{
		$x=$this->getRequestContent($this->plugins_server . "/plugins.json");
		return json_decode($x);
	}


}