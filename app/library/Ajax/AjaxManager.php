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
	private $msg_array = true;


	/**
	 * get ajax attributes 
	 * @param $key
	 * @param $default = null
	 * @return $info
	 */
	public function get($key , $default = null)
	{
		return $this->$key ?? $default;
	}

	/**
	 * set ajax attributes
	 * @param $key
	 * @param $val
	 */
	public function set($key , $val = null)
	{
		$this->$key = $val;
		return $this;
	}


	public function disableArray()
	{
		$this->msg_array = false;
	}
	public function enableArray()
	{
		$this->msg_array = false;
	}
	/**
	 * set ajax attributes
	 * @param $key
	 * @param $val
	 */
	public function clearMessages()
	{
		$this->messages = [];
		return $this;
	}

	/**
	 * set data info
	 * @param $key
	 * @param $val
	 */
	public function setData($val = null)
	{
		$this->data = $val;
		return $this;
	}

	/**
	 * add a message
	 * @param $msg : string
	 * @return $this : AjaxManager
	 */
	public function addMsg($msg , string $type = null)
	{	
		if ($type === null) 
			$this->messages[] = $msg;
		else
			$this->messages[$type] = $msg;
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
	 * @param $data : mixed
	 * @return $this : AjaxManager
	 */
	public function error($msg)
	{
		$this->status = 'danger';
		return $this->addMsg($msg , 'danger');
	}

	public function warning($msg)
	{
		$this->status = 'warning';
		return $this->addMsg($msg , 'warning');
	}

	public function success($msg)
	{
		$this->status = 'success';
		return $this->addMsg($msg , 'success');
	}
	public function notice($msg)
	{
		$this->status = 'notice';
		return $this->addMsg($msg , 'notice');
	}


	/**
	 * @return $this->response
	 */
	public function sendResponse()
	{
		$this->response->setJsonContent([
			'status'=> $this->status,
			'msg'=> $this->msg_array ? $this->messages : implode(", ", $this->messages),
			'data'=>$this->data
		]);
		
		if (true !== $this->response->isSent()) {
			return $this->response->send();
		}
		return $this->response;
	}
}