<?php 


namespace SakuraPanel\Library;


interface SharedConstInterface {

	// status
	const DELETED = -1;
	const INACTIVE = 0;
	const ACTIVE = 1;
	const SUSPENDED = 2;

	// roles
	const ADMIN = "admins";
	const MEMBER = "members";
	const GUEST = "guests";

	// status : enable/disable
	const DISABLED = self::INACTIVE;
	const ENABLED = self::ACTIVE;
	
	// const
	const DB_READ_KEY = 'model_read';


	// status
	const STATUS_LIST = [
      self::INACTIVE => 'InActive',
      self::ACTIVE => 'Active',
      self::SUSPENDED => 'Suspended',
      self::DELETED => 'Deleted',
    ];

	// roles
	const ROLES_LIST = [
      self::ADMIN => 'Admin',
      self::MEMBER => 'Member',
      self::GUEST => 'Guest',
    ];

	// date
	const DATE_FORMAT = "Y-m-d";	
	const TIME_FORMAT = "H:i:s";	
	const DATE_TIME_FORMAT = "y-m-d H:i:s";	


	// acl 
	const ROLE_DEFAULT = "guests";


	// plugins
	const PLUGIN_CONFIG_NAME = "plugin.config.php";
	const PLUGIN_CONFIG_JSON = "plugin.config.json";
		
}