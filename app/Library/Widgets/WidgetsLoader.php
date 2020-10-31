<?php 

namespace Sakura\Library\Widgets;

use Phalcon\Di\AbstractInjectionAware;

/**
 * WidgetsLoader
 */
class WidgetsLoader extends AbstractInjectionAware
{
	private $widgets = [];


	/**	
	 * Add Widget to List of Widgets
	 * @param partial : string
	 */
	public function addWidget(Widget $widget) : void
	{
		$this->widgets[] = $widget;
	}

	/**	
	 * Get Widgets
	 * @return $widgets : Array
	 */
	public function getWidgets() : Array
	{
		return $this->widgets;
	}
}