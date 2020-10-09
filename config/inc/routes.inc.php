<?php 

/**
 *
 *      Routers
 *      Configs
 *
 */

$configs['route_groups'] = array(


    'error'=>[
        "404"=>[
            'url'=>['/404','/#/','/404/(.*)'],
            'controller'=>'\Sakura\Controllers\Pages\PageErrors','action'=>'Page404',
            'access'=> '*'
        ],
        "503"=>[
            'url'=>['/503','/#/','/503/(.*)'],
            'controller'=>'\Sakura\Controllers\Pages\PageErrors','action'=>'Page503',
            'access' => '*'
        ],
    ],


    'member'=>[

        'dashboard'=>[
                'url'=>['/#','/#/','/#/@'],
                'controller'=>'\Sakura\Controllers\Member\Dashboard',
                'action'=>'index' , 
                'access' => 'members|admins'
        ],

        'settings'=>[
                'url'=>['/#/@','/#/@/'],
                'controller'=>'\Sakura\Controllers\Member\Account\Profilesettings',
                'action'=>'index' , 
                'access' => 'members|admins'
        ],




        'logout'=>[
                'url'=>['/#/@','/#/@/','/#/@/:params'],
                'controller'=>'\Sakura\Controllers\Member\Auth',
                'action'=>'logout', 
                'params'=>2 , 
                'access' => 'members|admins' 
        ],


        // auth
        'auth'=>[
                'url'=>['/', '/@','/@/','/@/:action/:params','/@/:action/:params/:token'],
                'controller'=>'\Sakura\Controllers\Auth\Auth',
                'action'=>1, 
                'params' =>2 , 
                'token' => 3, 
                'access' => '*'
        ],

    ],


    "admin"=>[

        'users'=>[
                'url'=>['/#/@/','/#/@', '/#/@/:action', '/#/@/:action/:params'],
                'controller'=>'\Sakura\Controllers\Admin\Users\Users',
                'action'=> 1 , 
                'params'=> 2 , 
                'access' => 'admins'
        ],

        'roles'=>[
            'url'=>['/#/@/','/#/@', '/#/@/:action', '/#/@/:action/:params'],
            'controller'=>'\Sakura\Controllers\Admin\Users\Roles',
            'action'=> 1 , 
            'params'=> 2 , 
            'access' => 'admins'
        ],

        'website-settings'=>[
                'url'=>['/#/@/','/#/@', '/#/@/:action', '/#/@/:action/:params'],
                'controller'=>'\Sakura\Controllers\Admin\Website\Settings',
                'action'=> 1 , 
                'params'=> 2 , 
                'access' => 'admins'
        ],

        'website-theme'=>[
                'url'=>['/#/@/','/#/@', '/#/@/:action', '/#/@/:action/:params'],
                'controller'=>'\Sakura\Controllers\Admin\Website\Theme',
                'action'=> 1 , 
                'params'=> 2 , 
                'access' => 'admins'
        ],

    ]
     
);
