<?php 
declare(strict_types=1);

namespace SakuraPanel\Controllers\Admin\Website;

use SakuraPanel\Controllers\Member\MemberControllerBase;
use SakuraPanel\Forms\{
	SiteConfigsForm
};

use SakuraPanel\Models\App\{
	SiteConfigs
};

use SakuraPanel\Library\DataTables\DataTable;


/**
 * Settings
 */
class SettingsController extends MemberControllerBase
{
    // Implement common logic
    public function initialize(){
    	parent::initialize();

        $this->page->set('title','Website Settings');
        $this->page->set('description','Here you can edit&update your website settings <b>Smile☻</b>.');

        $this->view->dataTable = true;
	
    }
	public function indexAction()
	{

		return $this->view->pick('admin/website/settings');
	}

	public function editAction($id = null)
	{
		$row = SiteConfigs::findFirstById($id);
		if (!$row) {
			$this->flashSession->error('Unknown config ID: '.intval($id));
			return $this->response->redirect('member/website-settings');
		}
		
		$form = new SiteConfigsForm($row);


		if (!empty($this->request->isPost())) {
			if (false === $form->isValid($_POST)) {
			    $messages = $form->getMessages();

			    foreach ($messages as $message) {
			        $this->flashSession->warning((string) $message);
			    }
			}else{
				$form->bind($_POST, $row);

				if ($row->save()) {
					$this->flashSession->success('Config Updated Successffully ');
					return $this->response->redirect('member/website-settings');
				}else{
					$this->flashSession->error('Error !' . implode(" & ", $row->getMessages()));
				}
			}
		}else{
			$form->bind($row->toArray() , $row);
		}


		$this->view->form = $form;
		$this->view->row = $row;

		$this->view->pick('admin/website/configEdit');
	}



	public function ajaxAction()
	{
		if ($this->request->isAjax()) {
          $builder = $this->modelsManager->createBuilder()
                          ->columns('id, key, val, type')
                          ->from(SiteConfigs::class);

          $dataTables = new DataTable();

          $dataTables->setOptions([
          	'limit'=> abs((int) $this->request->get('length'))
          ]);
          $dataTables->setIngoreUpperCase(true);
          
          $dataTables->fromBuilder($builder);

          $dataTables->addCustomColumn('actions' , function ($key , $data)
          {
          	return "<a class='btn btn-info btn-sakura btn-sm' href='member/website-settings/edit/$data[id]'><i class='fa fa-pencil'></i>Edit</a>";
          });

          $dataTables->sendResponse();
        }else{
        	return $this->response->redirect('member/website-settings');
        }
	}


}