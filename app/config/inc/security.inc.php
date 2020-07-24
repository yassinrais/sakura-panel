<?php 

$configs['security'] = [
	// validation
	'mailValidationLength' => getenv('SECURITY_MAIL_ACTTOKEN_LENGTH') ?: 15,
	'resetPasswordKeyLength' => getenv('SECURITY_RESET_PASSTOKEN_LENGTH') ?: 50,

	// expiration
	'authResetExpirationDelay' => getenv('SECURITY_EXPIRE_RESET_DELAY') ?: 60 * 60 *  24 * 1 , // 1 day 
	'authActivateExpirationDelay' => getenv('SECURITY_EXPIRE_ACTIVATION_DELAY') ?: 60 * 60 *  24 * 10 , // 10 day 
	
	// security
	'cacheSecurityLifeTime' => 60 * 60 * 24 * 30,
	'maxAuthAttemps'=> getenv('SECURITY_MAX_AUTH_ATTEMPS') ?:  2, // max 5 try 
	'authTTL' => getenv('SECURITY_AUTH_TTL') ?: 60 * 10, //(10 minutes)
	'activationMailDelay'=> getenv('SECURITY_SEND_ACTIVATION_DELAY') ?: 1  * 60  ,// 1 minute
	'fakeFailsDelay'=> rand(0, (int)getenv('SECURITY_FAKE_FAILS_DELAY') ?: 6), // 5 seconds

];

// for new we are not interested