<?php 

use Sakura\Library\Plugins\Plugin;

$plugin = new Plugin();

$plugin->initPluginByJson(dirname(__FILE__)."/plugin.config.json");

$plugin->addRoute(
	"admin", 
	"plugins" ,
	[
		'url'=>['/#/@/:action','/#/@/:action/:params'],
	    'controller'=>"\Sakura\Plugins\\${groupName}\Controllers\Plugins",
	    'action'=>1 , 
	    'params'=>2 , 
	    'access' => ['admins' => ['*'] ]
	]
)->addMenu(
	"Admin",
	"plugins",
	[
		"title"=>"Plugins",
		"icon"=>"fas fa-box",
		"url"=>"admin/plugins/all",
		"access"=>"admins",
	],
	false
);


return $plugin;