<?php 

use SakuraPanel\Library\Plugins\Plugin;

$plugin = new Plugin();

$plugin->initPlugin(
	"Discord Bots",
	"discordbots",
	"1.0.0",
	"Yassine Rais"
);

$plugin->addRoute(
	"member", 
	"discordbots" ,
	[
		'url'=>['/#/@','/#/@/','/#/@/:action','/#/@/:action/:params'],
        'controller'=>"\SakuraPanel\Plugins\\${groupName}\Controllers\DiscordBots",
        'action'=>1 , 
        'params'=>2 , 
        'access' => ['members|admins' => ['*'] ]
	]
)->addMenu(
	"Discord",
	"discordbots",
	[
		"title"=>"Discord Bots",
		"icon"=>"fas fa-robot",
		"url"=>"member/discordbots",
		"access"=>"members|admins",
	],
	3
);


return $plugin;
