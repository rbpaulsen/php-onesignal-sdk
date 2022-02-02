<?php

namespace NNV\OneSignal\API;

use \NNV\OneSignal\OneSignal;
use \NNV\OneSignal\Utils\Validation;
use Symfony\Component\OptionsResolver\Options;

/**
 * Player (Device) API
 */
class Player
{
    /**
     * OneSignal instance
     *
     * @var \NNV\OneSignal\OneSignal
     */
    private $oneSignal;

    /**
     * Application ID
     *
     * @var string $appId Application ID
     */
    private $appIDKey;

    /**
     * API Key for JSON API
     *
     * @var string API Key for JSON API
     */
    private $restAPIKey;

    /**
     * Extra options
     *
     * @var array Extra options for call Player API
     */
    private $extraOptions;

    /**
     * @param \NNV\OneSignal\OneSignal $oneSignal OneSignal instance
     * @param string $appIDKey Application ID
     * @param string $restAPIKey API Key for REST JSON API
     */
    public function __construct(OneSignal $oneSignal, $appIDKey = null, $restAPIKey = null)
    {
        $this->oneSignal    = $oneSignal;
        $this->appIDKey     = ($appIDKey ? $appIDKey : $oneSignal->getAppIDKey());
        $this->restAPIKey   = ($restAPIKey ? $restAPIKey : $oneSignal->getRESTAPIKey());
        $this->extraOptions = [
            'headers' => [
                'Authorization' => sprintf('Basic %s', $this->restAPIKey),
            ]
        ];
    }

    /**
     * Get all devices in application
     *
     * @var int How many devices to return
     * @var int Result offset. Result are sorted by id
     * @return \NNV\OneSignal\OneSignal::execute()
     */
    public function all($limit = 10, $offset = 0)
    {
        $url = sprintf(
            'players?app_id=%s&limit=%s&offset=&%s',
            $this->appIDKey,
            $limit,
            $offset
        );
        $devices = $this->oneSignal->execute($url, 'GET', $this->extraOptions);

        return $devices;
    }

    /**
     * View a device by Player id
     *
     * @param  string $playerID Player ID
     * @return \NNV\OneSignal\OneSignal::execute
     */
    public function get($playerID)
    {
        $url = sprintf('players/%s', $playerID);
        $players = $this->oneSignal->execute($url, 'GET', $this->extraOptions);

        return $players;
    }

    /**
     * Create new device (Player)
     *
     * @param  string $deviceType Device type. See more on \NNV\OneSignal\Constants\DeviceTypes
     * @param  array  $playerData Device details
     * @return \NNV\OneSignal\OneSignal::execute()
     */
    public function create($deviceType, array $playerData)
    {
        $playerData = array_merge($playerData, [
            'app_id' => $this->appIDKey,
            'device_type' => $deviceType
        ]);
        $playerData = $this->validatePlayerData($playerData, $this->getPlayerDataRules());
        $player = $this->oneSignal->execute('players', 'POST', [
            'form_params' => $playerData,
        ]);

        return $player;
    }

    /**
     * Edit an existing device in OneSignal.
     *
     * @param string $playerID Player ID to update
     * @param  array  $playerData Player data to update
     * @return \NNV\OneSignal\OneSignal::execute()
     */
    public function update($playerID, array $playerData)
    {
        $url = sprintf('players/%s', $playerID);
        $playerData = array_merge($playerData, [
            'app_id' => $this->appIDKey,
        ]);
        $playerDataRules = $this->getPlayerDataRules();

        unset($playerDataRules['required'][1]);

        $playerData = $this->validatePlayerData($playerData, $playerDataRules);
        $player = $this->oneSignal->execute($url, 'PUT', [
            'form_params' => $playerData,
        ]);

        return $player;
    }

    /**
     * Update a device's session information
     *
     * @param  string $playerID    PlayerID
     * @param  array  $sessionData New application session
     * @return \NNV\OneSignal\OneSignal::execute()
     */
    public function onSession($playerID, array $sessionData)
    {
        $url = sprintf('players/%s/on_session', $playerID);
        $playerSessionRules = $this->getPlayerDataRules();
        $unnecessaryDefinedOptions = [
            'app_id', 'device_type', 'session_count',
            'amount_spent', 'created_at', 'playtime',
            'badge_count', 'last_active', 'notification_types',
            'test_type', 'long', 'lat', 'device_model',
        ];
        $playerSessionRules['required'] = [];
        $sessionData = $this->validatePlayerData($sessionData, $playerSessionRules);

        foreach ($unnecessaryDefinedOptions as $definedOption) {
            unset($sessionData[$definedOption]);
        }

        $player = $this->oneSignal->execute($url, 'POST', [
            'form_params' => $sessionData,
        ]);

        return $player;
    }

    /**
     * Track a new purchase in your app
     *
     * @param  string $playerID     Player (device) ID
     * @param  array  $purchaseData Purcharse data
     * @param  bool   $isExisting Tracking the same purchases more than once if the user re-installs your app
     * @return \NNV\OneSignal\OneSignal::execute()
     */
    public function onPurchase($playerID, array $purchaseData, $isExisting = null)
    {
        $url = sprintf('players/%s/on_purchase', $playerID);
        $purchaseDataRules = $this->getPurchaseDataRules();

        if (isset($purchaseData[0]) && !is_array($purchaseData[0])) {
            $purchaseData = [$purchaseData];
        }
        foreach ($purchaseData as $purchase) {
            $this->validatePurchaseData($purchase, $purchaseDataRules);
        }

        $purchaseData = [
            'purchases' => $purchaseData,
        ];

        if (is_bool($isExisting)) {
            $purchaseData['existing'] = $isExisting;
        }

        $purchase = $this->oneSignal->execute($url, 'POST', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $purchaseData,
        ]);

        return $purchase;
    }

    /**
     * Update a device's session length upon app resuming
     *
     * @param  string $playerID  Player (device) ID
     * @param  array  $focusData Focus data
     * @return \NNV\OneSignal\OneSignal::execute()
     */
    public function onFocus($playerID, array $focusData)
    {
        $url = sprintf('players/%s/on_focus', $playerID);
        $onFocusDataRules = $this->getOnFocusDataRules();
        $this->validateOnFocusData($focusData, $onFocusDataRules);

        $onFocus = $this->oneSignal->execute($url, 'POST', [
            'form_params' => $focusData,
        ]);

        return $onFocus;
    }

    /**
     * Generate a compressed CSV export of all of your current user data
     *
     * @param  array  $extraFields Additional fields that you wish to include. Currently supports location, country, and rooted
     * @return object Object contain path to download csv gzip file
     */
    public function csvExport(array $extraFields = [])
    {
        $url = sprintf('players/csv_export?app_id=%s', $this->appIDKey);

        if (count($extraFields)) {
            $extraFields = [
                'extra_fields' => $extraFields,
            ];
        }

        return $this->oneSignal->execute($url, 'POST', array_merge([
            'form_params' => $extraFields,
        ], $this->extraOptions));
    }

    /**
     * Player (device) data validation rules
     *
     * @return array Validation rules
     */
    private function getPlayerDataRules()
    {
        return [
            'required' => ['app_id', 'device_type'],
            'defined' => [
                'app_id' => 'string',
                'device_type' => [
                    'allowedTypes' => 'int',
                    'allowedValues' => range(0, 9),
                ],
                'identifier' => 'string',
                'language' => 'string',
                'timezone' => 'int',
                'game_version' => 'string',
                'device_model' => 'string',
                'device_os' => 'string',
                'ad_id' => 'string',
                'sdk' => 'string',
                'session_count' => 'int',
                'tags' => 'array',
                'amount_spent' => 'string',
                'created_at' => 'int',
                'playtime' => 'init',
                'badge_count' => 'int',
                'last_active' => 'int',
                'notification_types' => [
                    'allowedTypes' => 'string',
                    'allowedValues' => [1, -2],
                ],
                'test_type' => [
                    'allowedTypes' => 'int',
                    'allowedValues' => [1, 2],
                ],
                'long' => 'double',
                'lat' => 'double',
                'external_user_id' => 'string',
            ],
        ];
    }

    /**
     * Player (device) purchase data validation rules
     *
     * @return array Validation rules
     */
    private function getPurchaseDataRules()
    {
        return [
            'required' => ['sku', 'amount', 'iso'],
            'defined' => [
                'sku' => 'string',
                'amount' => 'string',
                'iso' => 'string',
                'existing' => 'bool',
            ],
        ];
    }

    /**
     * Player (device) on focus data validation rules
     *
     * @return array Validation rules
     */
    private function getOnFocusDataRules()
    {
        return [
            'required' => ['state', 'active_time'],
            'defined' => [
                'state' => [
                    'allowedTypes' => 'string',
                    'allowedValues' => 'ping',
                ],
                'active_time' => 'int',
            ],
        ];
    }

    /**
     * Validate Player (device) data
     *
     * @param  array  $playerData Player (device) data
     * @param  array  $playerDataRules Player (device) data validation rules
     * @throws mixed NNV\OneSignal\Utils\Validation::validate or data resolved
     */
    private function validatePlayerData(array $playerData, array $playerDataRules)
    {
        $validation = new Validation();

        $validation->setMultiRequired($playerDataRules['required'])
                   ->setMultiDefined($playerDataRules['defined'])
                   ->setNormalizer('tags', function(Options $options, $tags) {
                        return json_decode(json_encode($tags));
                   })
                   ->setAllowedValues('amount_spent', function($amountSpent) {
                        return preg_match('/^\-?\d+(\.\d{1,2})?$/', $amountSpent);
                   });

        return $validation->validate($playerData);
    }

    /**
     * Validate Player (device) purchase data
     *
     * @param  array  $purchaseData Player (device) purchase data
     * @param  array  $purchaseDataRules Purchase data validation rules
     * @throws mixed NNV\OneSignal\Utils\Validation::validate or data resolved
     */
    private function validatePurchaseData(array $purchaseData, array $purchaseDataRules)
    {
        $validation = new Validation;

        $validation->setMultiRequired($purchaseDataRules['required'])
                   ->setMultiDefined($purchaseDataRules['defined'])
                   ->setAllowedValues('amount', function($amountSpent) {
                        return preg_match('/^\-?\d+(\.\d{1,2})?$/', $amountSpent);
                   });

        $validation->validate($purchaseData);
    }

    /**
     * Validate Player (device) focus data
     *
     * @param  array  $appData Player (device) purchase data
     * @param  array  $onFocusDataRules On focus data validation rules
     * @throws mixed NNV\OneSignal\Utils\Validation::validate or data resolved
     */
    private function validateOnFocusData(array $onFocusData, array $onFocusDataRules)
    {
        $validation = new Validation;

        $validation->setMultiRequired($onFocusDataRules['required'])
                   ->setMultiDefined($onFocusDataRules['defined'])
                   ->validate($onFocusData);
    }
}
