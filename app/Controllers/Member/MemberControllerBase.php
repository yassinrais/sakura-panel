<?php 
declare(strict_types=1);

namespace Sakura\Controllers\Member;
use Sakura\Controllers\Pages\{ PageControllerBase };
/**
 * MemberControllerBase
 */
class MemberControllerBase extends PageControllerBase
{
    // Implement common logic
    public function onConstruct(){
    	$this->authenticate();
    	
    	parent::initialize();
    }

    public function initialize()
    {
        $this->page->set('title','Dashboard');
        // $this->assetsPack->footer
            // ->addJs('');

    	$this->view->setMainView('member/index');
    }
	public function indexAction()
	{
		return $this->view->pick('member/dashboard/index');
	}
}