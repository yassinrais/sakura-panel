<?php 


/**
 *
 * Cache Life 
 * Configurations
 *
 */

$configs['cache'] = [
	'security_life_time'=> 60 * 60 * 24 * 30 , 
	'model_life_time'=> (int) getenv('CAHCE_MODEL_LIFE_TIME') ?: 6, // default 6 seconds
	'shared_life_time'=> (int) getenv('CAHCE_SHARED_LIFE_TIME') ?: 6, // default 6 seconds
];

