<?php 

namespace SakuraPanel\Library\Widgets;

use SakuraPanel\Controllers\ControllerBase;
/**
 * WidgetsLoader
 */
class WidgetsLoader extends ControllerBase
{
	private $widgetsPath;
	protected $widgets = [];
	protected $userWidgets;

	private $widgetKey = 'user_widgets';

	public function setWidgetsPath(string $path)
	{
		$this->widgetsPath = $path;
	}


	public function loadWidgets()
	{
		try {
			$files = scandir($this->widgetsPath);	
			foreach ($files as $fileName)
				if (strpos($fileName, ".volt") > -1){
					$p = $this->widgetsPath . $fileName;
					$p = str_replace(".volt", "", explode($this->config->application->viewsDir, $p)[1]);

					$this->widgets[strtolower(str_replace(".volt", "", $fileName))] =  ["path"=> "{$p}" , "status"=> "show"];  
				}
		} catch (Exception $e) {
			exit('ERROR');
		}
 
	}


	public function getWidgets()
	{
		$userWidgets = explode("|", $this->session->get($this->widgetKey) );

		foreach ($userWidgets as $widget) {
			$w = explode(":", $widget.":");
			$this->widgets[$w[0]] = array_merge($this->widgets[$w[0]] ?? [] , ["status"=> $w[1]]); 
		}


		return (object) $this->widgets;
	}

	public function run()
	{
		// nothing to do :) 
	}
	
}