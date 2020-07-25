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
            'controller'=>'SakuraPanel\Controllers\Pages\Home' , 'action'=>'index'
        ],
    ],
  
 
    'error'=>[
        "404"=>[
            'url'=>['/404','/#/','/404/(.*)'],
            'controller'=>'SakuraPanel\Controllers\Pages\Error','action'=>'Page404'
        ],
        "503"=>[
            'url'=>['/503','/#/','/503/(.*)'],
            'controller'=>'SakuraPanel\Controllers\Pages\Error','action'=>'Page503'
        ],
    ],


    'member'=>[

        'dashboard'=>[
                'url'=>['/#','/#/','/#/@'],
                'controller'=>'\SakuraPanel\Controllers\Member\Dashboard',
                'action'=>'index' , 
                'info'=>['title'=>'Dashboard','icon'=>'bx bx-home-circle','category'=>'general']
        ],


        // auth
        'auth'=>[
                'url'=>['/@','/@/','/@/:action/:params','/@/:action/:params/:token'],
                'controller'=>'\SakuraPanel\Controllers\Auth\Auth',
                'action'=>1, 
                'params' =>2 , 
                'token' => 3, 
                'info'=>['title'=>'Auth','icon'=>'bx bx-cog','hide'=>true]
        ],
        'logout'=>[
                'url'=>['/#/@','/#/@/','/#/@/:params'],
                'controller'=>'\SakuraPanel\Controllers\Member\Auth',
                'action'=>'logout', 
                'params'=>2 , 
                'info'=>['title'=>'Logout','icon'=>'bx bx-power-off','category'=>'account']
        ]
    ],
     
);
