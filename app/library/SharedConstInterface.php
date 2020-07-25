<?php 


namespace SakuraPanel\Library;


interface SharedConstInterface {

	// status
	const DELETED = -1;
	const INACTIVE = 0;
	const ACTIVE = 1;
	const SUSPENDED = 2;

	// date
	const DATE_FORMAT = "y-m-d";	
	const TIME_FORMAT = "H:i:s";	
	const DATE_TIME_FORMAT = "y-m-d H:i:s";	

	
}