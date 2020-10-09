<?php 
declare(strict_types=1);

namespace Sakura\Controllers\Pages;


class PageErrorsController extends PageControllerBase{


	public function Page404Action(){
		if ($this->request->isAjax()) {
			return '{"message":"an error ocurred"}';
		}
		return $this->view->pick('errors/404');
	}


	public function Page503Action()
	{
		if ($this->request->isAjax()) {
			return '{"message":"an error ocurred"}';
		}
		return $this->view->pick('errors/503');
	}

}