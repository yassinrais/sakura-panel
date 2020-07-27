<?php 

/**
 *
 *      Routers
 *      Configs
 *
 */

$configs['sections'] = array(

    'home'=>[
        "index"=>[
            'url'=>['/#','/'],
            'controller'=>'SakuraPanel\Controllers\Pages\Home' , 'action'=>'index',
            'access' => ['geusts' => ['*'] ]
        ],
    ],
  
 
    'error'=>[
        "404"=>[
            'url'=>['/404','/#/','/404/(.*)'],
            'controller'=>'SakuraPanel\Controllers\Pages\PageErrors','action'=>'Page404',
            'access'=> ['*' => ['*']]
        ],
        "503"=>[
            'url'=>['/503','/#/','/503/(.*)'],
            'controller'=>'SakuraPanel\Controllers\Pages\PageErrors','action'=>'Page503',
            'access' => ['*' => ['*'] ]
        ],
    ],


    'member'=>[

        'dashboard'=>[
                'url'=>['/#','/#/','/#/@'],
                'controller'=>'\SakuraPanel\Controllers\Member\Dashboard',
                'action'=>'index' , 
                'access' => ['members|admins' => ['*'] ]
        ],

        'settings'=>[
                'url'=>['/#','/#/','/#/@'],
                'controller'=>'\SakuraPanel\Controllers\Member\Account\Profilesettings',
                'action'=>'index' , 
                'access' => ['members|admins' => ['*'] ]
        ],



        'users'=>[
                'url'=>['/#/@/','/#/@', '/#/@/:action', '/#/@/:action/:params'],
                'controller'=>'\SakuraPanel\Controllers\Admin\Users\Users',
                'action'=> 1 , 
                'params'=> 2 , 
                'access' => ['admins' => ['*'] ]
        ],


        'website-settings'=>[
                'url'=>['/#/@/','/#/@', '/#/@/:action', '/#/@/:action/:params'],
                'controller'=>'\SakuraPanel\Controllers\Admin\Website\Settings',
                'action'=> 1 , 
                'params'=> 2 , 
                'access' => ['admins' => ['*'] ]
        ],




        'logout'=>[
                'url'=>['/#/@','/#/@/','/#/@/:params'],
                'controller'=>'\SakuraPanel\Controllers\Member\Auth',
                'action'=>'logout', 
                'params'=>2 , 
                'access' => ['members|admins' => ['*'] ]
        ],


        // auth
        'auth'=>[
                'url'=>['/@','/@/','/@/:action/:params','/@/:action/:params/:token'],
                'controller'=>'\SakuraPanel\Controllers\Auth\Auth',
                'action'=>1, 
                'params' =>2 , 
                'token' => 3, 
                'access' => ['*' => ['*'] ]
        ],

    ],
     
);
