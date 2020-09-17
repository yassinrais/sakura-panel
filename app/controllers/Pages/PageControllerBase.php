<?php 
declare(strict_types=1);

namespace SakuraPanel\Controllers\Pages;

use \Phalcon\Mvc\Controller;

use \SakuraPanel\Library\SharedConstInterface;
use \SakuraPanel\Plugins\Auth\AuthMiddleware;


/**
 * PageControllerBase
 */
class PageControllerBase extends AuthMiddleware implements SharedConstInterface
{
	public $assetsPack;

	public function initialize()
	{
		$this->assetsPack = new \stdClass();
		// save assets
		$aHeader = $this->assets->collection('header')
			->addCss('https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet')
			->addCss('assets/vendor/fontawesome-free/css/all.min.css')
			->addCss('assets/css/sb-admin-2.css')
			->addCss('assets/vendor/sweet-alert2/sweetalert2.min.css')
			->addCss('assets/css/panel.css')
			->addCss('assets/custom/custom.css')
			;

		$aFooter = $this->assets->collection('footer')
			->addJs('assets/vendor/jquery/jquery.min.js')
			->addJs('assets/vendor/jquery-cookie/jquery.cookie.js')
			->addJs('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')
			->addJs('assets/vendor/jquery-easing/jquery.easing.min.js')
			->addJs('assets/vendor/sweet-alert2/sweetalert2.all.min.js')
			->addJs('assets/js/sb-admin-2.min.js')
			->addJs('assets/custom/custom.js');


		$aDataTables = $this->assets->collection('dataTable')
			->addCss('assets/vendor/datatables/dataTables.bootstrap4.min.css')
			->addJs('assets/vendor/datatables/jquery.dataTables.min.js')
			->addJs('assets/vendor/datatables/dataTables.bootstrap4.min.js')
			->addJs('assets/js/tables/all.js');

		// save in attribute
		$this->assetsPack->header = $aHeader;
		$this->assetsPack->footer = $aFooter;
		$this->assetsPack->dataTable = $aDataTables;
	}
	
	public function indexAction()
	{
		return '404';
	}
}