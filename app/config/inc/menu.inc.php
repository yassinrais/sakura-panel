<?php 


$configs['menu'] = [
	"General"=>[
		"items"=>[
			"dashboard"=>[
				"title"=>"Dashboard",
				"icon"=>"fas fa-tachometer-alt",
				"url"=>"member/dashboard",


				"access"=>"members|admins",
			],

		]
	],

	"Admin"=>[
		"items"=>[
			"users"=>[
				"title"=>"Users",
				"icon"=>"fas fa-users",
				"url"=>"member/users",


				"access"=>"admins",
			],

		]
	],

	"Account"=>[
		"items"=>[

			"settings"=>[
				"title"=>"Settings",
				"icon"=>"fas fa-cogs",
				"url"=>"member/settings",


				"access"=>"members|admins",
			],



			"logout"=>[
				"title"=>"Logout",
				"icon"=>"fas fa-sign-out-alt",
				"attrs"=>'href="#" data-toggle="modal" data-target="#logoutModal"',

				"access"=>"*",
			],
		]
	]

];
