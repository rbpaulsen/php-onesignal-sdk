<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

use NNV\OneSignal\OneSignal;
use NNV\OneSignal\API\App;

$oneSignal = new OneSignal('YOUR_USER_AUTH_KEY');
$app = new App($oneSignal);

# Find app by appId
// dump($app->find('APP_ID'));

# Get all apps
// $apps = $app->get();
// dump($apps);

# Create app
// $appData = [
//     'name' => 'Test from API',
//     'apns_env' => 'sanbox',
// ];

// dump($app->create($appData));

# Update app
$appData = [
    'name' => 'Test from API updated',
];
dump($app->update('APP_ID', $appData));
