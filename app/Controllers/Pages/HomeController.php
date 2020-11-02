<?php 
declare(strict_types=1);

namespace Sakura\Controllers\Pages;


/**
 * HomeController
 */
class HomeController extends PageControllerBase
{	
    // Implement common logic
    public function onConstruct(){
    	$this->authenticate();
    }
    
	public function indexAction(){
		return $this->view->pick('home');
	}	

	
}