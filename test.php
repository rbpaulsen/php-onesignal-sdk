<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
load_env(__DIR__);

use NNV\OneSignal\OneSignal;
use NNV\OneSignal\API\App;
use NNV\OneSignal\API\Player;
use NNV\OneSignal\Utils\Validation;
use NNV\OneSignal\API\Notification;

$demoAppID = env('APP_ID');
$demoAppRestKey = env('APP_REST_KEY');

$oneSignal = new OneSignal(env('USER_AUTH_KEY'), $demoAppID, $demoAppRestKey);
$app = new App($oneSignal);
$player = new Player($oneSignal);
$notification = new Notification($oneSignal);
$otherOneSignal = $oneSignal->copy(null, 'Other AppID', 'Other RESTful API key', [
    'timeout' => 20,
]);

# Find app by appId
// dump($app->get('APP_ID'));

# Get all apps
// $apps = $app->all();
// dump($apps);

# Create app
// $appData = [
//     'name' => 'Test from API',
//     'apns_env' => 'sanbox',
// ];

// dump($app->create($appData));

# Update app
// $appData = [
//     'name' => 'Test from API updated',
// ];
// dump($app->update('APP_ID', $appData));


$rules = [
    'required' => [
        'name',
        'email'
    ],
    'defined' => [
        'name' => 'string',
        'email' => [
            'allowedTypes' => 'string',
        ],
        'gender' => [
            'allowedTypes' => 'string',
            'allowedValues' => ['Male', 'Female', 'Unknow'],
        ]
    ]
];
$data = [
    'email' => 'namnv609',
    'name' => '123456',
    'gender' => 'ABC'
];
$notificationData = [
    'included_segments' => ['All', 'Free User'],
    'contents' => [
        'en' => 'Welcome',
        'vi' => 'Chao mung',
    ],
    'headings' => [
        'en' => 'Hello',
        'vi' => 'Xin chao'
    ],
    'buttons' => [
        [
            'id' => 'id1',
            'text' => 'Button 1',
            'icon' => 'icon_1'
        ],
        [
            'id' => 'id2',
            'text' => 'Button 2',
            'icon' => 'icon_2'
        ]
    ],
    // 'filters' => [
    //     [
    //         'field' => 'tag',
    //         'key' => 'level',
    //         'relation' => '>',
    //         'value' => '10'
    //     ],
    //     [
    //         'field' => 'amount_spent',
    //         'relation' => '>',
    //         'value' => '0'
    //     ]
    // ],
    'send_after' => 'Sep 24 2017 14:00:00 GMT-0700',
    'delivery_time_of_day' => '09:00AM',
    // 'include_player_ids' => ['2445818a-8e72-4477-a014-de8667d860a2'],
    // 'isAndroid' => true,
];



$validation = new Validation();
$validation->setMultiRequired($rules['required'])
           ->setMultiDefined($rules['defined'])
           ->setAllowedValues('email', function($emailAddr) {
                return filter_var($emailAddr, FILTER_VALIDATE_EMAIL);
           });
// $validation->validate($data);

\Psy\Shell::debug(get_defined_vars());
