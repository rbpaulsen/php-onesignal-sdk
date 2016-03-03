<?php

namespace NNV\OneSignal;

use GuzzleHttp\Client;

class OneSignal
{
    /**
     * API base URI
     *
     * @var string
     */
    private $apiBaseURI;

    /**
     * User Auth Key
     *
     * @var string
     */
    private $userAuthKey;

    /**
     * App ID Key
     *
     * @var string
     */
    private $appIDKey;

    /**
     * REST API Key
     *
     * @var string
     */
    private $restAPIKey;

    /**
     * @var GuzzleHttp\Client
     */
    private $guzzleClient;

    public function __construct($userAuthKey, $appIDKey = null, $restAPIKey = null, $options = array())
    {
        $this->apiBaseURI = 'https://onesignal.com/api/v1/';
        $this->userAuthKey = $userAuthKey;
        $this->appIDKey = $appIDKey;
        $this->restAPIKey = $restAPIKey;

        $options = $this->getDefaultOptions($options);
        $this->guzzleClient = new Client(array(
            'base_uri' => $this->apiBaseURI,
            'timeout' => $options['timeout'],
        ));
    }

    public function getAPIBaseURI()
    {
        return $this->apiBaseURI;
    }

    public function getUserAuth()
    {
        return $this->userAuthKey;
    }

    public function setAppIDKey($appIDKey)
    {
        $this->appIDKey = $appIDKey;

        return $this;
    }

    public function getAppIDKey()
    {
        return $this->appIDKey;
    }

    public function setRESTAPIKey($restAPIKey)
    {
        $this->restAPIKey = $restAPIKey;

        return $this;
    }

    private function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'timeout' => 30,
        );

        foreach ($defaultOptions as $option => $value) {
            if (isset($options[$option])) {
                $defaultOptions[$option] = $options[$option];
            }
        }

        return $defaultOptions;
    }
}
