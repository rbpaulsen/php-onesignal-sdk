# PHP SDK for OneSignal RESTful API

> OneSignal is a high volume and reliable push notification service for websites and mobile applications. We support all major native and mobile platforms by providing dedicated SDKs for each platform, a RESTful server API, and an online dashboard for marketers to design and send push notifications.

## System requirements
* **PHP >= 5.5

## Installation
Using Composer
`composer install namnv609/php-onesignal-sdk`
or you can include the following in your `composer.json`
`"namnv609/php-onesignal-sdk": "1.0"`
### Response format
```JSON
{"status":true,"code":200,"response":<OneSignal result>}
```
* `status`: Boolean. `true` or `false` (status code is 200 or otherwise)
* `code`: Integer. Status code
* `response`: Mixed. You can view OneSignal result detail for each API at: [https://documentation.onesignal.com/reference](https://documentation.onesignal.com/reference)

```PHP
$players = $player->all();

foreach ($players->response->players as $player) {
    echo $player->id . PHP_EOL;
}
```

## Usage Instructions

First, create a new `OneSignal` instance to make configuring the library for usage.

```PHP
use NNV\OneSignal\OneSignal;

$oneSignal = new OneSignal(<User Auth key> [, <App ID>, <App REST key>, <Extra options for GuzzleHttp Client>])
```

Once the `OneSignal` instance has been registered. You may use it like so:

### [Application](http://namnv609.github.io/php-onesignal-sdk/class-NNV.OneSignal.API.App.html)
Application body parameters: [**Create**](https://documentation.onesignal.com/reference#create-an-app) and [**Update**](https://documentation.onesignal.com/reference#update-an-app)
```PHP
use NNV\OneSignal\API\App;

$app = new App($oneSignal);
```
* View apps
```PHP
$app->all();
```
* View an app
```PHP
$app->get("<App ID>");
```
* Create an app
```PHP
$appData = [
    'name' => '<App name>',
    'apns_env' => 'sandbox',
];

$app->create($appData);
```
* Update an app
```PHP
$appData = [
    'apns_env' => 'production',
];

$app->update("<App ID>", $appData);
```

### [Player (Device)](http://namnv609.github.io/php-onesignal-sdk/class-NNV.OneSignal.API.Player.html)
Player (Device) body parameters: [**Create**](https://documentation.onesignal.com/reference#add-a-device),  [**Update**](https://documentation.onesignal.com/reference#edit-device), [**New session**](https://documentation.onesignal.com/reference#new-session), [**New purchase**](https://documentation.onesignal.com/reference#new-purchase), [**Increment session length**](https://documentation.onesignal.com/reference#increment-session-length) and [**CSV export**](https://documentation.onesignal.com/reference#csv-export)
```PHP
use NNV\OneSignal\API\Player;

$player = new Player($oneSignal [, <App ID>, <App REST key>]);
```
* View devices
```PHP
$player->all([<Limit>, <Offset>]);
```
* View device
```PHP
$player->get("<Player ID>");
```
* Add a device
```PHP
use NNV\OneSignal\Constants\DeviceTypes;

$playerData = [
    'language' => 'en',
    'tags' => [
        'for' => 'bar',
        'this' => 'that'
    ]
];

$player->create(DeviceTypes::CHROME_WEBSITE, $playerData);
```
* Edit device
```PHP
use NNV\OneSignal\Constants\NotificationTypes;
use NNV\OneSignal\Constants\TestTypes;

$playerData = [
    'test_type' => TestTypes::DEVELOPMENT,
    'notification_types' => NotificationTypes::UNSUBSCRIBED
];

$player->update("<Player ID>", $playerData);
```
* New session
```PHP
$sessionData = [
    'tags' => [
        'new' => 'session',
    ],
];
$player->onSession("<Player ID>", $sessionData);
```
* New purchase (Currently, i've support one item per request)
```PHP
$purchaseData = [
    'sku' => 'SKU123',
    'iso' => 'USD',
    'amount' => '0.99',
];

$player->onPurchase("<Player ID>", $purchaseData, [<Is existing>]);
```
* Increment session length
```PHP
$focusData = [
    'state' => 'ping',
    'active_time' => 1,
];

$player->onFocus("<App ID>", $focusData);
```
* CSV export
```PHP
$extraFields = ['rooted'];

$player->csvExport($extraFields);
```

### [Notification](http://namnv609.github.io/php-onesignal-sdk/class-NNV.OneSignal.API.Notification.html)
Notification body parameters: [**Create**](https://documentation.onesignal.com/reference#create-notification)
```PHP
use NNV\OneSignal\API\Notification;

$notification = new Notification($oneSignal[, <App ID>, <App REST key>]);
```
* Create notification
```PHP
$notificationData = [
    'included_segments' => ['All'],
    'contents' => [
        'en' => 'Hello, world',
    ],
    'headings' => [
        'en' => 'Hello',
    ],
    'buttons' => [
        [
            'id' => 'button_id',
            'text' => 'Button text',
            'icon' => 'button_icon',
        ],
    ],
    'filters' => [
        [
            'field' => 'tag',
            'key' => 'level',
            'relation' => '>',
            'value' => '10',
        ],
    ],
    'send_after' => 'Sep 24 2017 14:00:00 GMT-0700',
    'isChromeWeb' => true,
];

$notification->create($notificationData);
```
* Cancel notification
```PHP
$notification->cancel("<Notification ID>");
```
* View notification
```PHP
$notification->get("<Notification ID>");
```
* View notifications
```PHP
$notification->all([<Limit>, <Offset>]);
```
* Track open
```PHP
$notification->trackOpen("<Notification ID>");
```
