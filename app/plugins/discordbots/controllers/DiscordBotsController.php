<?php 

namespace SakuraPanel\Plugins\Discordbots\Controllers;


use SakuraPanel\Controllers\Member\{
	MemberControllerBase
};


/**
 * DiscordBotsController
 */
class DiscordBotsController extends MemberControllerBase
{

	public function initialize(){
		parent::initialize();
		
		$this->page->set('title', 'Discord BOT\'s Manager');
	}

	public function indexAction()
	{
		return $this->view->pick('plugins/discordbots/index');
	}	
}