<?php 

namespace SakuraPanel\Plugins\Products\Controllers;


use SakuraPanel\Controllers\Member\{
	MemberControllerBase
};

use SakuraPanel\Plugins\Products\Models\{
	Products
};

use SakuraPanel\Library\DataTable\DataTable;

use SakuraPanel\Plugins\Products\Forms\ProductSForm;

/**
 * ProductSController
 */
class ProductSController extends MemberControllerBase
{

	public function initialize(){
		parent::initialize();
		
		$this->page->set('base_route' , 'member/products');
		$this->page->set('title', 'Products Manager');
        $this->page->set('description','Here you can manager all products bots <b>Smile☻</b>.');
		
        $this->view->dataTable = true;
	}

	public function indexAction()
	{
		return $this->view->pick('plugins/products/index');
	}	
	public function createAction()
	{
		$row = new ProductS();
		$form = new ProductSForm($row);


		if ($this->request->isPost()) {
			$row->user_id = (int) $this->di->get('user')->id;


            if (false === $form->isValid($_POST)) {
			    $messages = $form->getMessages();

			    foreach ($messages as $message)
			        $this->flashSession->warning((string) $message);
			}else{
				$form->bind($this->request->getPost(), $row);
				$row->status  ="1";

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

		return $this->view->pick('plugins/products/form');
	}
	public function editAction($id = null)
	{
		if (!is_null($id)) {
			$row = Products::findFirstById($id);

			if ($row) {
				if ($this->request->isPost()) {
					$form = new ProductSForm($row);


		            if (false === $form->isValid($_POST)) {
					    $messages = $form->getMessages();

					    foreach ($messages as $message)
					        $this->flashSession->warning((string) $message);
					}else{
						$form->bind($_POST, $row);

						if ($row->save()) {
							$this->flashSession->success('Change saved successfully ! ');
						}else{
						    foreach ($row->getMessages() as $message)
						        $this->flashSession->warning((string) $message);
						}

					}
				}


				$this->view->row = $row;

				$this->view->form = new ProductSForm($row);
				$this->view->form->bind($row->toArray() , $row);

				return $this->view->pick('plugins/products/form');
			}


		}
		return $this->response->redirect($this->page->get('base_route'));
	}	


	public function ajaxAction()
	{
		if ($this->request->isAjax()) {
          $builder = $this->modelsManager->createBuilder()
                          ->columns('id, name, title, [note], image , status')
                          ->from(Products::class);

          $dataTables = new DataTable();
          $dataTables->setIngoreUpperCase(true);
          
          $dataTables->fromBuilder($builder)
          ->addCustomColumn('c_status' , function ($key , $data) {
          	$s = Products::getStatusById($data['status']);
          	return "<span class='btn btn-$s->color btn-icon-split btn-sm p-0'>
				<span class='icon text-white-50'>
				  <i class='fas fa-$s->icon' style='width:20px'></i>
				</span>
				<span class='text'>$s->title</span>
			</span>";
          })
          ->addCustomColumn('image' , function ($key , $data)
          {
          	return "<img src='$data[image]' style='max-width:200px;'>";
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

	public function deleteAction()
	{
		$resp = $this::jsonStatus('error','Unknown error','danger');
		if ($this->request->isAjax()) {
			$id = (int) $this->request->get('id');

			$row = Products::findFirstById($id);

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

			$row = Products::findFirstById($id);

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


}