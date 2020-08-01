<?php
declare(strict_types=1);


use \Phalcon\Mvc\Controller;
use \SakuraPanel\Library\SharedConstInterface;


class ControllerBase extends Controller implements SharedConstInterface
{

	public function jsonStatus($status ='success', $msg ='Unknown msg! ',$type = 'success')
	{
		return [
  			'type'=>$type,
  			'msg'=>$msg,
  			'status'=>$status,
  		];
	}
}
