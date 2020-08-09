<?php
declare(strict_types=1);


use \Phalcon\Mvc\Controller;
use \SakuraPanel\Library\SharedConstInterface;


class ControllerBase extends Controller implements SharedConstInterface
{

	/**
	 * Clean Path slashDupicate
	 * @param $path : string
	 * @return $path_clean : string
	 */
	public function cleanPath(string $path) : string
	{
		return preg_replace('#/+#','/',$path);
	}
}
