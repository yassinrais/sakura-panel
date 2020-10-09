<?php 
namespace Sakura\Plugins\__group__\Controllers;


// auth midleware : MemberControllerBase
use Sakura\Controllers\Member\{
	MemberControllerBase
};

// use Sakura\Plugins\__group__\Models\{__group__ , modelName2};

// use Sakura\Plugins\__group__\Forms\{FormName1 , FormName2};

// use Sakura\Library\Datatables\DataTable;

/**
 * __group__Controller
 */
class __group__Controller extends MemberControllerBase
{	
	// init page info (if its a view controller :) )
	public function initialize(){
		parent::initialize();
		
		$this->page->set('base_route' , 'member/__name__');
		$this->page->set('title', '__title__');
        $this->page->set('description','__description__');
		
        $this->view->dataTable = true;
	}

	// index page
	public function indexAction()
	{
		$this->view->pick('plugins/__route__/index');
	}


}