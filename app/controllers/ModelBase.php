<?php
declare(strict_types=1);

use \Phalcon\Mvc\Model;
use \SakuraPanel\Library\SharedConstInterface;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class ModelBase extends Model implements SharedConstInterface
{

	protected $client_ip;

	public function skipAllAttributes()
	{
		$metadata = $this->getModelsMetaData();
		$attributes = $metadata->getAttributes($this);

		$this->skipAttributes($attributes);
	}

	public function getSourceByName($name = null)
	{
		return $this->getDI()->getConfig()->tables->{$name} ?? $name;
	}


	public function beforeCreate()
	{
	 	if (!empty($this->created_at) || isset($this->created_at)) {
			// Set the creation date / ip
		    $this->created_at = (int) time();
		    $this->created_ip = $this->getIp();
		}

	}

	public function beforeUpdate()
	{

		if (!empty($this->updated_at) || isset($this->updated_at)) {
		    $this->updated_at = (int) time();
		    $this->updated_ip = $this->getIp();
		}

	}


	public function beforeDelete(){



        if (!empty($this->_safe_delete) && $this->_safe_delete == true) {

              $this->addBehavior(
                    new SoftDelete([    'field' => 'status',        'value' => $this::DELETED     ])
              );
              $this->addBehavior(
                    new SoftDelete([    'field' => 'deleted_at',    'value' => (int) time()       ])
              );
              $this->addBehavior(
                    new SoftDelete([    'field' => 'deleted_ip',    'value' => $this::getIp()     ])
              );

        }
	
  }


	/**
	* @return $ip : string
	*/
	public function getIp()
	{
	    return $this->client_ip ?: "Unknown";
	}
	
	/**
	* @param $request / $ip
	*/
	public function setIp($request=null)
	{
	    $this->client_ip = is_string($request) ? $request : ((!is_null($request) && !empty($request->getClientAddress())) ? $request->getClientAddress() : null);
	} 


	/**
	 * get status by id
	 */
	public function getStatusInfo()
	{
		return self::getStatusById($this->status);
	}

	public static function getStatusById($status)
	{
		$info = (object) [
			'title'=>'Unknown',
			'icon'=>'close',
			'id'=>-2,
			'type'=>'error',
			'color'=>'danger',
		];

		switch ($status ?: null) {
			case self::DELETED:
				$info->title = "Deleted";
				$info->icon = "trash";
				break;
			case self::INACTIVE:
				$info->title = "InActive";
				$info->type = "warning";
				$info->icon = "exclamation";
				$info->color = "info";
				break;
			case self::ACTIVE:
				$info->title = "Active";
				$info->type = "success";
				$info->icon = "check-square";
				$info->color = "success";
				break;
			case self::SUSPENDED:
				$info->title = "Suspended";
				$info->icon = "minus";
				$info->color = "warning";
				break;

			default:
				
				break;
		}

		return (object) $info;
	}

	public function isActive()
	{
		return ( $this->status ?? null ) == $this::ACTIVE; 
	}
	
}