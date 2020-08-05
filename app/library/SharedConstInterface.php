<?php 


namespace SakuraPanel\Library;


interface SharedConstInterface {

	// status
	const DELETED = -1;
	const INACTIVE = 0;
	const ACTIVE = 1;
	const SUSPENDED = 2;

	// status : enable/disable
	const DISABLED = self::INACTIVE;
	const ENABLED = self::ACTIVE;
	
	// const
	const DB_READ_KEY = 'model_read';


	const STATUS_LIST = [
      self::INACTIVE => 'InActive',
      self::ACTIVE => 'Active',
      self::SUSPENDED => 'Suspended',
      self::DELETED => 'Deleted',
    ];

	// date
	const DATE_FORMAT = "y-m-d";	
	const TIME_FORMAT = "H:i:s";	
	const DATE_TIME_FORMAT = "y-m-d H:i:s";	


	// acl 
	const ROLE_DEFAULT = "geusts";


	// plugins
	const PLUGIN_CONFIG_NAME = "plugin.config.php";
	const PLUGIN_CONFIG_JSON = "plugin.config.json";
		
}