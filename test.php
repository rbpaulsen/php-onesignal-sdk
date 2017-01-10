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

$demoAppID = env("APP_ID");
$demoAppRestKey = env("APP_REST_KEY");

$oneSignal = new OneSignal(env("USER_AUTH_KEY"));
$app = new App($oneSignal);
$player = new Player($oneSignal, $demoAppID, $demoAppRestKey);

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
        'name' => [
            'types' => 'string',
        ],
        'email' => [
            'types' => function() {
                return false;
            },
        ],
        'gender' => [
            'types' => 'string',
            'values' => ['Male', 'Female', 'Unknow'],
        ]
    ]
];
$data = [
    'email' => 'namnv609@gmail.com',
    'name' => '123456',
    'gender' => 'ABC'
];
$validate = new Validation();

\Psy\Shell::debug(get_defined_vars());
