<?php 
declare(strict_types=1);

namespace SakuraPanel\Controllers\Pages;

use Phalcon\Mvc\Controller;

/**
 * PageControllerBase
 */
class PageControllerBase extends Controller
{

	public function indexAction()
	{
		return '404';
	}
}