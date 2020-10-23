<?php 
declare(strict_types=1);

namespace Sakura\Controllers\Member;

use Sakura\Models\User\{
	Users
};
use Sakura\Models\App\{
	SiteConfigs
};

/**
 * Dashboard
 */
class DashboardController extends MemberControllerBase
{
    // Implement common logic
    public function initialize(){
    	parent::initialize();

        $this->page->set('title','Dashboard');
    }

	public function indexAction()
	{
		$this->view->total = (object) [
			'users' => Users::find()->count(),
			'configs' => SiteConfigs::find()->count(),
		];

		return $this->view->pick('member/dashboard/index');
	}
}