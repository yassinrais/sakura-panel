<?php 

/**
 * Route/access Config
 */
$configs['route_groups'] = array_merge_recursive(
	$configs['route_groups'] ,
	[
		"member"=>[
			"test"=>[
				'url'=>['/#/@','/#/@/'],
                'controller'=>"\SakuraPanel\Plugins\\${groupName}\Controllers\DiscordBots",
                'action'=>'index' , 
                'access' => ['members|admins' => ['*'] ]
            ]
		]
	] 
);
/**
 * Menu/access Configs
 */
$configs['menu']['Admin']['items'] = array_merge_recursive(
	$configs['menu']['Admin']['items'],
	[
		"test"=>[
			"title"=>"Discord Bots",
			"icon"=>"fas fa-check",
			"url"=>"member/test",


			"access"=>"admins",
		],
	]
);