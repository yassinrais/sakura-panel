<?php 

/**
 * Route/access Config
 */
$configs['route_groups'] = array_merge_recursive(
	$configs['route_groups'] ,
	[
		"member"=>[
			"discordbots"=>[
				'url'=>['/#/@','/#/@/','/#/@/:action','/#/@/:action/:params'],
                'controller'=>"\SakuraPanel\Plugins\\${groupName}\Controllers\DiscordBots",
                'action'=>1 , 
                'params'=>2 , 
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
		"discordbots"=>[
			"title"=>"Discord Bots",
			"icon"=>"fas fa-robot",
			"url"=>"member/discordbots",


			"access"=>"admins",
		],
	]
);