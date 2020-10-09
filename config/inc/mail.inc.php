<?php 

$configs['mail'] = [
	'fromName' => getenv('MAIL_FROM_NAME') ?? 'Sakura Panel',
	'fromEmail' => getenv('MAIL_FROM_ADDRESS') ?? 'info@sakura.io',
	'mailer'=>getenv('MAIL_MAILER') ?? 'mail',
    'smtp' => [
        'server' => getenv('MAIL_HOST') ?? 'mail.sakura.io',
        'port' => getenv('MAIL_PORT') ?? 465,
        'security' => getenv('MAIL_ENCRYPTION') ?? null,
        'username' => getenv('MAIL_USERNAME') ?? null,
        'password' => getenv('MAIL_PASSWORD') ?? null,
    ],
];