<?php
declare(strict_types=1);

namespace Sakura\Models;

/**
 * Phalcon depend
 */
use \Phalcon\Mvc\Model;
use \Phalcon\Mvc\Model\Behavior\SoftDelete;

/**
 * Sakura Shared const list
 */
use \Sakura\Library\SharedConstInterface;

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
	 	if (property_exists($this, 'created_at') || !empty($this->created_at) || isset($this->created_at)) {
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

	 	if (property_exists($this, 'updated_at') || !empty($this->updated_at) || isset($this->updated_at)) {
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
			property_exists($this, '_safe_delete') || property_exists(static::class, '_safe_delete')
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

	/**	
	 * Get Random Token
	 */
	public static function generateToken($l = 100)
	{
		$random = new \Phalcon\Security\Random();

		return $random->base58($l);
	}

	/**	
	 * Clear Cache by key
	 */
	public function clearCache($key = null)
	{
		$key = md5($key);
		$modelCache = $this->getDI()->get('modelsCache');
		
		$keys = $modelCache->queryKeys($key);
		if (is_array($keys))
			foreach ($keys as $k )
				$modelCache->delete($k);
				
	}


	/**	
	 * generatedKeyCache by 
	 * @param $paramters | array
	 */
	protected static function generateCacheKey(array $parameters)
    {
        $uniqueKey = [];

        foreach ($parameters as $key => $value) {
            if (true === is_scalar($value)) {
                $uniqueKey[] = $key . ':' . $value;
            } elseif (true === is_array($value)) {
                $uniqueKey[] = sprintf(
                    '%s:[%s]',
                    $key,
                    self::generateCacheKey($value)
                );
            }
        }

        return self::class .'x'. md5(join(',', $uniqueKey));
	}
	
	/**	
	 * Generated Cache Key & return the new parametres
	 * @param $parameters | array 
	 * @return $parameters | array
	 */
	protected static function checkCacheParameters($parameters = null)
    {

        if (null !== $parameters && getenv('DEV_MODE') != "true") {
            if (true !== is_array($parameters)) {
                $parameters = [$parameters];
            }
    
            if (true !== isset($parameters['cache'])) {
                $parameters['cache'] = [
                    'key'      => self::generateCacheKey($parameters)
                ];
            }
        }
        
        return $parameters;
	}
	
	/**	
	 * Get Model Attributes
	 * @return $attributes | array, phalcon object
	 */
	public function getAttributes()
	{
		$metaData = $this->getModelsMetaData();
		return $metaData->getAttributes($this);
	}

	/**	
	 * Find or create method
	 */
	public static function findOrCreate($parameters = [] , $columnsData = []){
		$find = self::findFirst($parameters);
		if ($find)
			return $find;

		$class = get_called_class();
		$row = new $class();
		foreach($parameters['bind'] ?? [] as $column => $value){
			$row->$column = $value;
		}
		foreach($columnsData as $column => $value)
			$row->$column = $value;

		$row->save();
		

		return $row;
	}

	
	/**	
	 * Get Storage Path 
	 */
	public function getStoragePath()
    {
		$config = $this->getDI()->get('config');
		
		$date = date($config->storage->format);
	 
		$path = ($config->storage->path ?: "storage/" ) . $date . '/';
		$dir = ($config->storage->dir ?: "storage/" ) . $date . '/';
		
		$this->uploadDir = $dir;
		$this->uploadPath = $path;

        if(!is_dir($dir))
            try{
                mkdir($dir,0777, true);
            }catch(\Exception $e){
                $this->di->get('logger')->error('Storage Dir Creation was Failed ! ' .$e->GetMessage());
            }

        return $path;
    }
}