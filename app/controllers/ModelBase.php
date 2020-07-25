<?php
declare(strict_types=1);

use \Phalcon\Mvc\Model;
use \SakuraPanel\Library\SharedConstInterface;


class ModelBase extends Model implements SharedConstInterface
{
	protected $client_ip;

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
              
              if (isset($this->deleted)) 
              {
                    $this->addBehavior(
                          new SoftDelete(
                                [
                                      'field' => 'deleted',
                                      'value' => $this::DELETED,
                                ]
                          )
                          );

              }

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
	    $this->client_ip = is_string($request) ? $request : ((!empty($request->getClientAddress())) ? $request->getClientAddress() : null);
	} 


	/**
	 * get status by id
	 */
	public function getStatus()
	{
		$info = (object) [
			'title'=>'Unknown',
			'id'=>-2,
			'type'=>'error'
		];

		switch ($this->status ?? null) {
			case $this::DELETED:
				$info->title = "Deleted";
				break;
			case $this::INACTIVE:
				$info->title = "InActive";
				$info->type = "warning";
				break;
			case $this::ACTIVE:
				$info->title = "Active";
				$info->type = "success";
				break;
			case $this::SUSPENDED:
				$info->title = "Suspended";
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