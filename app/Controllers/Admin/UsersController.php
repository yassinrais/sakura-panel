<?php 
declare(strict_types=1);

namespace Sakura\Controllers\Admin;

use Sakura\Controllers\Member\MemberControllerBase;
use Sakura\Forms\Users\UsersForm;

use Sakura\Models\User\Users;

use Sakura\Library\Datatables\DataTable;


/**
 * Users
 */
class UsersController extends MemberControllerBase
{
    // Implement common logic
    public function initialize(){
    	parent::initialize();

        $this->page->set('title','Users');
        $this->page->set('description','Here you can manager all users in this website.');
        $this->page->set('base_route','admin/users');
        $this->view->dataTable = true;
	
    }
	public function indexAction()
	{

		return $this->view->pick('admin/users/list');
	}

	public function ajaxAction()
	{
          $builder = $this->modelsManager->createBuilder()
                          ->columns('id, fullname, email, status ,role_name')
                          ->from(Users::class);

          $dataTables = new DataTable();
          $dataTables->setIngoreUpperCase(true);
          $dataTables->fromBuilder($builder)
           ->addCustomColumn('c_role' , function ($key , $data) {
                $s = Users::getRoleByName($data['role_name']);
                return "<span class='btn btn-$s->color btn-icon-split btn-sm p-0'>
                <span class='icon text-white-50'>
                    <i class='fas fa-$s->icon' style='width:20px'></i>
                </span>
                <span class='text'>$s->title</span>
            </span>";
            })
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
                    "<span title='Delete' data-action ='delete' data-id='$id' class='ml-1 btn btn-danger btn-circle btn-sm table-action-btn'><i class='fas fa-trash'></i></span>";
                
                if ($data['status'] == $this::DELETED)
                    $actions .= 
                        "<span title='Restore' data-action='restore' data-id='$id' class='ml-1 btn btn-info btn-circle btn-sm table-action-btn' ><i class='fas fa-trash-restore'></i></span>";
                    
                if ($data['status'] != $this::ACTIVE)
                    $actions .= 
                        "<span title='Active' data-action='active' data-id='$id' class='ml-1 btn btn-success btn-circle btn-sm table-action-btn' ><i class='fas fa-check-circle'></i></span>";
                    
                if ($data['status'] == $this::ACTIVE)
                    $actions .= 
                        "<span title='Suspend' data-action='active' data-id='$id' class='ml-1 btn btn-primary btn-circle btn-sm table-action-btn' ><i class='fas fa-minus-circle'></i></span>";
                    
                $actions .= 
                    "<a href='{$this->page->get('base_route')}/edit/$id' class='ml-1 btn btn-warning btn-circle btn-sm ' ><i class='fas fa-edit'></i></a>";


                return $actions;
            })
            ->sendResponse();
    }
	
	/**
	 * Add New Row
	 */
    public function createAction()
	{
		$row = new Users();
		$form = new UsersForm($row);

		if (!empty($this->request->isPost())) {
			if (false === $form->isValid($_POST)) {
			    $messages = $form->getMessages();

			    foreach ($messages as $message) {
			        $this->flashSession->warning((string) $message);
			    }
			}else{
				$form->bind($_POST, $row);

				if ($row->save()) {
					$row->password = null;
					$this->flashSession->success("User {$row->username} created  Successffully ");
					return $this->response->redirect('admin/users');
				}else{
					$this->flashSession->error('Error !' . implode(" & ", $row->getMessages()));
				}
			}
		}else{
			$form->bind($row->toArray() , $row);
		}


		$this->view->form = $form;
		$this->view->row = $row;

		$this->view->pick('admin/users/form');
    }
	
	/**
	 * Edit Row
	 */
    public function editAction($id = null)
	{
		$row = Users::findFirstById($id);
		if (!$row) {
			$this->flashSession->error('Unknown User ID: '.intval($id));
			return $this->response->redirect('admin/users');
		}
		
		$form = new UsersForm($row);
		
		if (!empty($this->request->isPost())) {
			if (false === $form->isValid($_POST)) {
			    $messages = $form->getMessages();

			    foreach ($messages as $message) {
			        $this->flashSession->warning((string) $message);
			    }
			}else{
				if(empty($_POST['password']))
					unset($_POST['password']);
				
				$form->bind($_POST, $row);
				if ($row->save()) {
					$this->flashSession->success('User Updated Successffully ');
					return $this->response->redirect('admin/users');
				}else{
					$this->flashSession->error('Error ! ' . implode(" & ", $row->getMessages()));
				}
			}
		}else{
			$row->password = null;
			$form->bind($row->toArray() , $row);
		}


		$this->view->form = $form;
		$this->view->row = $row;

		$this->view->pick('admin/users/form');
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

    /** 
     * Active/InActive Users
     */
	public function activeAction()
	{
		if ($this->request->isAjax()) {
			$id = (int) $this->request->get('id');

			$row = Users::findFirstById($id);
			if (!$row) {
				return $this->ajax->error('Unknown row id '.$id)->sendResponse();
			}else{
                $row->disableValidation();
                $row->status = ($row->status == $this::ACTIVE) ? $this::INACTIVE  : $this::ACTIVE;

                if ($row->save()) {
					return $this->ajax->success("Row $id {$row->getStatusInfo()->title} successfully !")->sendResponse();
				}else{
					return $this->ajax->error("Row $id {$row->getStatusInfo()->title} failed ! \n".implode("&", $row->getMessages()))->sendResponse();
				}
			}

        }
		return $this->ajax->error('Unknown error')->sendResponse();
	}

}