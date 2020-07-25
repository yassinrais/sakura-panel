<?php 
declare(strict_types=1);

namespace SakuraPanel\Controllers\Member;

/**
 * Dashboard
 */
class DashboardController extends MemberControllerBase
{
    // Implement common logic
    public function initialize(){
        $this->page->set('title','Dashboard');
        
    	parent::initialize();
    }

	public function indexAction()
	{
		return $this->view->pick('member/dashboard/index');
	}
}