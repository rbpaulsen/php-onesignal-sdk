<?php

namespace NNV\OneSignal\API;

use \NNV\OneSignal\OneSignal;

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
        $this->oneSignal = $oneSignal;
        $this->appIDKey = $appIDKey;
        $this->restAPIKey = $restAPIKey;


        if (!$this->appIDKey) {
            $this->appIDKey = $oneSignal->getAppIDKey();
        }
        if (!$this->restAPIKey) {
            $this->restAPIKey = $oneSignal->getRESTAPIKey();
        }

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
        $player = $this->oneSignal->execute($url, 'PUT', [
            'form_params' => $playerData,
        ]);

        return $player;
    }

    /**
     * Call on new device session in your app
     *
     * @param  string $playerID    PlayerID
     * @param  array  $sessionData New application session
     * @return \NNV\OneSignal\OneSignal::execute()
     */
    public function onSession($playerID, array $sessionData)
    {
        $url = sprintf('players/%s/on_session', $playerID);
        $player = $this->oneSignal->execute($url, 'POST', [
            'form_data' => $sessionData,
        ]);

        return $player;
    }

    public function onPurchase($playerID, array $purchaseData)
    {
        $url = sprintf("players/%s/on_purchase", $playerID);
    }
}
