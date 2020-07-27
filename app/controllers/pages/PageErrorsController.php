<?php 
declare(strict_types=1);

namespace SakuraPanel\Controllers\Pages;


class PageErrorsController extends PageControllerBase{


	public function Page404Action(){
		return $this->view->pick('errors/404');
	}


	public function Page503Action()
	{
		return $this->view->pick('errors/503');
	}

}