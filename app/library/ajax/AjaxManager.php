<?php 

namespace SakuraPanel\Library\Ajax;


/**
 * AjaxManager
 */
class AjaxManager extends \ControllerBase
{
	private $messages = [];
	private $data = [];
	private $status = "success";


	/**
	 * get page info 
	 * @param $key
	 * @param $default = null
	 * @return $info
	 */
	public function get($key , $default = null)
	{
		return $this->$key ?? $default;
	}

	/**
	 * set page info
	 * @param $key
	 * @param $val
	 */
	public function set($key , $val = null)
	{
		$this->$key = $val;
		return $this;
	}

	/**
	 * add a message
	 * @param $msg : string
	 * @return $this : AjaxManager
	 */
	public function addMsg($msg)
	{
		$this->messages[] = $msg;
		return $this;
	}

	/**
	 * add a data
	 * @param $data : string
	 * @return $this : AjaxManager
	 */
	public function addData($data , $key = null)
	{
		if ($key == null)
			$this->data[] = $data;
		else
			$this->data[$key] = $data;

		return $this;
	}

	/**
	 * add a message
	 * @param $key : string
	 * @param $data : mixed
	 * @return $this : AjaxManager
	 */
	public function setData($key , $data)
	{
		$this->data[$key] = $data;
		return $this;
	}


	public function error($msg)
	{
		$this->status = 'danger';
		$this->addMsg($msg);
	}

	public function warning($msg)
	{
		$this->status = 'warning';
		$this->addMsg($msg);
	}

	public function success($msg)
	{
		$this->status = 'success';
		$this->addMsg($msg);
	}
	public function notice($msg)
	{
		$this->status = 'notice';
		$this->addMsg($msg);
	}


	/**
	 * @return $this->response
	 */
	public function sendResponse()
	{
		$this->response->setJsonContent([
			'status'=> $this->status,
			'msg'=> implode(", ", $this->messages),
			'data'=>$this->data
		]);
		

		return $this->response->send();
	}
}