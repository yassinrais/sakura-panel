<?php 
declare(strict_types=1);

namespace SakuraPanel\Controllers\Pages;


class PageErrorsController extends PageControllerBase{


	public function Page404Action(){
		return '404 - error';
	}


	public function Page503Action()
	{
		return '503 - error';
	}

}