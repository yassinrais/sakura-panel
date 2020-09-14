<?php 
/**
 * Panel Menu (sidebar)
 */

$configs['menu'] = [
	"general"=>[
		"order"=>0,
		"items"=>[
			"dashboard"=>[
				"title"=>"Dashboard",
				"icon"=>"fas fa-tachometer-alt",
				"url"=>"member/dashboard",


				"access"=>"members|admins",
			],

		]
	],

	"account"=>[
		"order"=>998,
		"items"=>[

			"settings"=>[
				"title"=>"Settings",
				"icon"=>"fas fa-user-circle",
				"url"=>"member/settings",


				"access"=>"members|admins",
			],



			"logout"=>[
				"title"=>"Logout",
				"icon"=>"fas fa-sign-out-alt",
				"url"=>"auth/logout",
				// "attrs"=>'data-toggle="modal" data-target="#logoutModal"',

				"access"=>"*",
			],
		]
	],
	

	"admin"=>[
		"order"=>999,
		"items"=>[
			"users"=>[
				"title"=>"Users",
				"icon"=>"fas fa-users",
				"url"=>"admin/users",


				"access"=>"admins",
			],

			"roles"=>[
				"title"=>"Roles",
				"icon"=>"fas fa-user-secret",
				"url"=>"admin/roles",


				"access"=>"admins",
			],

			"website-settings"=>[
				"title"=>"Website Settings",
				"icon"=>"fas fa-cogs",
				"url"=>"admin/website-settings",


				"access"=>"admins",
			],

		]
	],

];
