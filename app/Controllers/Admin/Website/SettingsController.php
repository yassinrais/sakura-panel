<?php 
declare(strict_types=1);

namespace Sakura\Controllers\Admin\Website;

use Sakura\Models\Sakura\SiteConfigs;

use Sakura\Forms\Website\SiteConfigsForm;

use Sakura\Controllers\Member\MemberControllerBase;


use Sakura\Library\Datatables\DataTable;


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
			return $this->response->redirect('admin/website-settings');
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
					return $this->response->redirect('admin/website-settings');
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

          $dataTables->addCustomColumn('c_actions' , function ($key , $data)
          {
			  return  "<a class='btn btn-info btn-sakura btn-sm' href='admin/website-settings/edit/$data[id]'><i class='fa fa-pencil'></i>Edit</a>";
          });

          	return $dataTables->sendResponse();
        }else{
			return $this->ajax->disableArray()->error('Only Ajax Method is alowed ! ')->sendResponse();
        }
	}


}