<?php 
declare(strict_types=1);

namespace SakuraPanel\Controllers\Admin\Users;

use SakuraPanel\Controllers\Member\MemberControllerBase;
use SakuraPanel\Forms\{
	UsersForm
};

use SakuraPanel\Models\User\{
	Users
};

use SakuraPanel\Library\DataTables\DataTable;


/**
 * Users
 */
class UsersController extends MemberControllerBase
{
    // Implement common logic
    public function initialize(){
    	parent::initialize();

        $this->page->set('title','Users');
        $this->page->set('description','Here you can manager all users in this website <b>Smile☻</b>.');

        $this->view->dataTable = true;
	
    }
	public function indexAction()
	{

		return $this->view->pick('admin/users/list');
	}

	public function ajaxAction()
	{
		if ($this->request->isAjax()) {
          $builder = $this->modelsManager->createBuilder()
                          ->columns('id, fullname, email, status')
                          ->from(Users::class);

          $dataTables = new DataTable();
          $dataTables->setIngoreUpperCase(true);
          $dataTables->fromBuilder($builder)
           ->addCustomColumn('c_status' , function ($key , $data) {
                $s = Users::getStatusById($data['status']);
                return "<span class='btn btn-$s->color btn-icon-split btn-sm p-0'>
                <span class='icon text-white-50'>
                    <i class='fas fa-$s->icon' style='width:20px'></i>
                </span>
                <span class='text'>$s->title</span>
            </span>";
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

                $actions .= 
                    "<a href='{$this->page->get('base_route')}/edit/$id' class='ml-1 btn btn-warning btn-circle btn-sm ' ><i class='fas fa-edit'></i></a>";


                return $actions;
            })
            ->sendResponse();
        }
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
    
    /** 
     * Delete user and put it in trash cron tasks (mean cron will delete the user after a while time )
     */
	public function deleteAction()
	{
		if ($this->request->isAjax()) {
			$id = (int) $this->request->get('id');

			$row = Users::findFirstById($id);

			if (!$row) {
				return $this->ajax->error('Unknown row id '.$id)->sendResponse();
			}else{
				$row->status = $this::DELETED;

				if ($row->delete()) {
					return $this->ajax->success("Row $id deleted successfully !")->sendResponse();
				}else{
					return $this->ajax->error("Row $id deleted failed ! \n".implode("&", $row->getMessages()))->sendResponse();
				}
			}

        }
		return $this->ajax->error('Unknown error')->sendResponse();
	}

    /** 
     * Restore Users before being deleted by cron
     */
	public function restoreAction()
	{
		if ($this->request->isAjax()) {
			$id = (int) $this->request->get('id');

			$row = Users::findFirstById($id);
			if (!$row) {
				return $this->ajax->error('Unknown row id '.$id)->sendResponse();
			}else{
                $row->disableValidation();
				$row->status = $this::INACTIVE;

				if ($row->save()) {
					return $this->ajax->success("Row $id restore successfully !")->sendResponse();
				}else{
					return $this->ajax->error("Row $id restore failed ! \n".implode("&", $row->getMessages()))->sendResponse();
				}
			}

        }
		return $this->ajax->error('Unknown error')->sendResponse();
	}

}