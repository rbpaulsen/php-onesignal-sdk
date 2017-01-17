<?php

namespace NNV\OneSignal\API;

use NNV\OneSignal\OneSignal;

class Notification
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
     * @var string
     */
    private $appIDKey;

    /**
     * RESTful API Key
     *
     * @var string
     */
    private $restAPIKey;

    /**
     * Extra options
     *
     * @var array
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
}
