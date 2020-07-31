<?php 

use SakuraPanel\Library\Plugins\Plugin;

$plugin = new Plugin();

$plugin->initPlugin(
	"Products Example",
	"products-example",
	"1.0.1",
	"Yassine Rs"
);

$plugin->addRoute(
	"member", 
	"products" ,
	[
		'url'=>['/#/@','/#/@/','/#/@/:action','/#/@/:action/:params'],
	    'controller'=>"\SakuraPanel\Plugins\\${groupName}\Controllers\Products",
	    'action'=>1 , 
	    'params'=>2 , 
	    'access' => ['members|admins' => ['*'] ]
	]
)->addMenu(
	"Products",
	"products",
	[
		"title"=>"Producuts",
		"icon"=>"fas fa-box",
		"url"=>"member/products",
		"access"=>"members|admins",
	],
	2
);



return $plugin;