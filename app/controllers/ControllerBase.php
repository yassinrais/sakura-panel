<?php
declare(strict_types=1);


use \Phalcon\Mvc\Controller;

class ControllerBase extends Controller
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
