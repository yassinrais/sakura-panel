<?php 
declare(strict_types=1);

namespace SakuraPanel\Controllers\Member;
use SakuraPanel\Controllers\Pages\{ PageControllerBase };
/**
 * Dashboard
 */
class DashboardController extends PageControllerBase
{
    // Implement common logic
    public function onConstruct(){
    	$this->authenticate();
    	
    	parent::initialize();
    }

    public function initialize()
    {
    	$this->view->setMainView('member/index');
    }
	public function indexAction()
	{
		return $this->view->pick('member/dashboard/index');
	}
}