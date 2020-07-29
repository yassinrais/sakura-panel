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

use SakuraPanel\Library\DataTable\DataTable;


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
                          ->columns('id, fullname, email')
                          ->from(Users::class);

          $dataTables = new DataTable();
          $dataTables->setIngoreUpperCase(true);
          $dataTables->fromBuilder($builder)->sendResponse();
        }
	}

}