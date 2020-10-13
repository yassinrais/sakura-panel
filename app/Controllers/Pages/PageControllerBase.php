<?php 
declare(strict_types=1);

namespace Sakura\Controllers\Pages;

use \Phalcon\Mvc\Controller;

use \Sakura\Library\SharedConstInterface;
use \Sakura\Plugins\Auth\AuthMiddleware;
use \Sakura\Controllers\Admin\Website\ThemeController;
use \Sakura\Helpers\Functions;
/**
 * PageControllerBase
 */
class PageControllerBase extends AuthMiddleware implements SharedConstInterface
{
	protected $assetsPack;
    protected $allowedFileTypes = [
        "css","js","min.js","min.css","ttf","otf","jpg","png","jpeg"
    ];


	public function initialize()
	{
		$this->assetsPack = new \stdClass();
		// save assets
		$aHeader = $this->assets->collection('header')
			->addCss('https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet')
			->addCss('assets/vendor/fontawesome-free/css/all.min.css')
			->addCss('assets/css/sb-admin-2.css')
			->addCss('assets/vendor/sweet-alert2/sweetalert2.min.css')
			->addCss('assets/css/panel.css');

		$aFooter = $this->assets->collection('footer')
			->addJs('assets/vendor/jquery/jquery.min.js')
			->addJs('assets/vendor/jquery-cookie/jquery.cookie.js')
			->addJs('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')
			->addJs('assets/vendor/jquery-easing/jquery.easing.min.js')
			->addJs('assets/vendor/sweet-alert2/sweetalert2.all.min.js')
			->addJs('assets/js/sb-admin-2.min.js');


		$aDataTables = $this->assets->collection('dataTable')
			->addCss('assets/vendor/datatables/dataTables.bootstrap4.min.css')
			->addJs('assets/vendor/datatables/jquery.dataTables.min.js')
			->addJs('assets/vendor/datatables/dataTables.bootstrap4.min.js')
			->addJs('assets/js/tables/all.js');

		// save in attribute
		$this->assetsPack->header = $aHeader;
		$this->assetsPack->footer = $aFooter;
		$this->assetsPack->dataTable = $aDataTables;

		$this->addCustomAssets();

		$this->view->t = $this->translator;
	}

	/**
	 * 
	 * Method For Custom Assets 
	 * Responsable about creating and merge the custom scripts/styles 
	 * 
	 */
	public function addCustomAssets()
	{
		$c = $this->getThemeFiles();
		$p = $this->getCustomFilesPath();

		// create new collection custom
		$assetCss = $this->assets->collection('customcss');
		$assetJs = $this->assets->collection('customjs');

		foreach($c as $f){
			switch ($f['type']){
				case 'css':
					$assetCss->addCss($f['path']);
				break;
				case 'js':
					$assetJs->addJs($f['path']);
				break;
			}
		}
		$assetCss->addFilter( new \Phalcon\Assets\Filters\Cssmin() );
		$assetJs->addFilter( new \Phalcon\Assets\Filters\Jsmin() );

		$assetCss->setTargetPath("assets/css/custom.min.css")
				->setTargetUri("assets/css/custom.min.css")
				->join(true);
		
		$assetJs->setTargetPath("assets/js/custom.min.js")
				->setTargetUri("assets/js/custom.min.js")
				->join(true);

		$this->assetsPack->customCss = $assetCss;
		$this->assetsPack->customJs = $assetJs;
	} 

	
	/** 
     * get the custom files path 
     * @return $path | String
     */
    public function getCustomFilesPath()
    {
        return $this->config->theme->path ?? "public/assets/custom/";
	}

	/** 
     * Get Allowed File to Edit/Delete
     * @return $files | Array
     */
    public function getThemeFiles() : Array
    {
        $files = [];

        $dirFiles = Functions::_sortDirFiles($this->getCustomFilesPath());

        foreach($dirFiles as $file){
            $filePath = $this->getCustomFilesPath() . $file;

            if (!in_array($file, ['.','..'])){
                $type = pathinfo($file, PATHINFO_EXTENSION);
                if (in_array($type, $this->allowedFileTypes)){
                    $fsize = Functions::_convertSize(filesize($filePath));
                    $mtype = mime_content_type($filePath);

                    $files[$filePath] = [
                        "name"=> $file,
                        "type"=> $type,
                        "mtype"=> $mtype,
						"size"=> $fsize,
						"path"=> $filePath,
                        "link"=> urlencode($filePath)
                    ];
                }
            }
        }

        return $files;
	}
	
	
	
	/**	
	 * Default Index Action
	 */
	public function indexAction()
	{
		return '404';
	}

}