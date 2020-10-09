<?php 

use Sakura\Library\Plugins\Plugin;

$plugin = new Plugin();

$plugin->initPluginByJson(dirname(__FILE__)."/plugin.config.json");

$plugin->addRoute(
	"member", 
	"__route__" ,
	[
		'url'=>['/#/@','/#/@/','/#/@/:action','/#/@/:action/:params'],
	    'controller'=>"\Sakura\Plugins\\${groupName}\Controllers\__group__",
	    'action'=>1 , 
	    'params'=>2 , 
	    'access' => ['admins' => ['*'] ]
	]
)
->addMenu(
	"__title__",
	"__route__",
	[
		"title"=>"__title__",
		"icon"=>"fas fa-box",
		"url"=>"member/__route__",
		"access"=>"admins",
	]
)
// ->addSql([
// 	'install'=>dirname(__FILE__).'/sql/install.sql',
// 	'delete'=>dirname(__FILE__).'/sql/delete.sql'
// ])
;

return $plugin;