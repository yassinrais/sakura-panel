<?php 

use SakuraPanel\Library\Plugins\Plugin;

$plugin = new Plugin();

$plugin->initPluginByJson(dirname(__FILE__)."/plugin.config.json");

$plugin->addRoute(
	"member", 
	"plugins" ,
	[
		'url'=>['/#/@','/#/@/','/#/@/:action','/#/@/:action/:params'],
	    'controller'=>"\SakuraPanel\Plugins\\${groupName}\Controllers\Plugins",
	    'action'=>1 , 
	    'params'=>2 , 
	    'access' => ['members|admins' => ['*'] ]
	]
)->addMenu(
	"Admin",
	"plugins",
	[
		"title"=>"Plugins Manager",
		"icon"=>"fas fa-box",
		"url"=>"member/plugins",
		"access"=>"admins",
		"sub"=>[
			[
				"title"=>"Installed Plugins",
				"icon"=>"fas fa-box-open",
				"url"=>"member/plugins/installed",
				"access"=>"admins",
			],
			[
				"title"=>"All Plugins",
				"icon"=>"fas fa-box-open",
				"url"=>"member/plugins/all",
				"access"=>"admins",
			],
		]
	]
);


return $plugin;