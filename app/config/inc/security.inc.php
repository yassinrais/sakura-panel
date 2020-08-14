<?php 
/**
 *
 * Security Configs 
 * Configurations
 *
 */

$configs['security'] = [
	// validation
	'mail_activation_token_length' => getenv('SECURITY_MAIL_ACTTOKEN_LENGTH') ?? 15,
	'password_reset_token_lenth' => getenv('SECURITY_RESET_PASSTOKEN_LENGTH') ?? 50,

	// max attemps
	'auth_suspend_max_attemps'=> getenv('SECURITY_AUTH_SUSPEND_MAX_ATTEMPS') ??  2, // max 5 try 
	'auth_banned_max_attemps'=> getenv('SECURITY_AUTH_BANNED_MAX_ATTEMPS') ??  2, // max 5 try 

	// delays
	'activation_send_delay'=> getenv('SECURITY_SEND_ACTIVATION_DELAY') ?? 1  * 60  ,// 1 minute
	'auth_fake_delay'=> rand(0, getenv('SECURITY_AUTH_FAKE_DELAY') ?? 6), // 5 seconds

	// password 
	'min_password_length'=> getenv('SECURITY_PASSWORD_MIN_LENGTH') ?? 5  ,// 1 minute
	'max_password_length'=> getenv('SECURITY_PASSWORD_MAX_LENGTH') ?? 60  ,// 1 minute
];

// for new we are not interested