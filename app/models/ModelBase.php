<?php
declare(strict_types=1);

namespace SakuraPanel\Models;

/**
 * Phalcon depend
 */
use \Phalcon\Mvc\Model;
use \Phalcon\Mvc\Model\Behavior\SoftDelete;

/**
 * Sakura Shared const list
 */
use \SakuraPanel\Library\SharedConstInterface;

/**
 * This is the base model for all used models 
 * features:
 * 		dynamic auto fill date create/update/delete
 *		dynamic safe delete rows
 *		dynamic set ip 
 *		status decode (getStatusInfo / isActive)
 *		Sahred constant from SharedConstInterface
 */
class ModelBase extends Model implements SharedConstInterface
{

	protected $client_ip;
	protected $_ignore_validation = false;

	/**
	 * Get the table name 
	 * @param $name : string 
	 * @return $name : string
	 */
	public function getSourceByName(string $name = null): string
	{
		return $this->getDI()->getConfig()->tables->{$name} ?? $name;
	}

	/**
	 * Dynamic Method to auto fill created_* columns
	 */
	public function beforeCreate()
	{
	 	if (property_exists(self::class, 'created_at') || !empty($this->created_at) || isset($this->created_at)) {
			// Set the creation date / ip
		    $this->created_at = (int) time();
		    $this->created_ip = $this->getIp();
		}

	}
	/**
	 * Dynamic Method to auto fill updated_* columns
	 */
	public function beforeUpdate()
	{

	 	if (property_exists(self::class, 'updated_at') || !empty($this->updated_at) || isset($this->updated_at)) {
		    $this->updated_at = (int) time();
		    $this->updated_ip = $this->getIp();
		}

	}

	/**
	 * Dynamic Method to auto Safe Delete & Fill updated_* columns
	 */
	public function beforeDelete()
	{
        if ((
			property_exists(self::class, '_safe_delete') || property_exists(static::class, '_safe_delete')
		) && !empty($this->_safe_delete) && $this->_safe_delete == true) {

              $this->addBehavior(
                    new SoftDelete([    'field' => 'status',        'value' => $this::DELETED     ])
              );
              $this->addBehavior(
                    new SoftDelete([    'field' => 'deleted_at',    'value' => (int) time()       ])
              );
              $this->addBehavior(
                    new SoftDelete([    'field' => 'deleted_ip',    'value' => $this::getIp()     ])
			  );
			  
			  $this->_ignore_validation = true;

        }
	
    }


	/**
	 * Getter clinet_ip 
	 * @return $ip : string
	 */
	public function getIp() : string
	{
	    return $this->client_ip ?: "Unknown";
	}
	
	/**
	 * Setter client_ip
	 * @param $request / $ip : mixed
	 */
	public function setIp($request=null) : void
	{
	    $this->client_ip = is_string($request) ? $request : ((!is_null($request) && !empty($request->getClientAddress())) ? $request->getClientAddress() : null);
	} 


	/**
	 * get status info by self::status
	 * @return $status : object
	 */
	public function getStatusInfo() : object
	{
		return self::getStatusById($this->status);
	}

	/**
	 * Static :: Get Status by the id
	 * @param $status : mixed
	 * @return $status : object 
	 */
	public static function getStatusById($status) : object
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
		}

		return (object) $info;
	}

	public function isActive()
	{
		return ( $this->status ?? null ) == $this::ACTIVE; 
	}
	
	public function getTimeFormate(int $secs = 0)
	{
	    $dt = new DateTime('@' . $secs, new DateTimeZone('UTC'));
	    $x = array('days'    => $dt->format('z'),
	                 'hours'   => $dt->format('G'),
	                 'minutes' => $dt->format('i'),
	                 'seconds' => $dt->format('s'));

	    $d = [];
	    foreach ($x as $key => $value) {
	    	if ($value > 0) {
	    		$d[] = "$value $key";
	    	}
	    }

	    return implode(", ", $d);
	}


	/**	
	 * Method to disable validation 
	 */
	public function disableValidation(){
		$this->_ignore_validation = true;
	}

	/**	
	 * Method to enable validation 
	 */
	public function enableValidation(){
		$this->_ignore_validation = false;
	}
}