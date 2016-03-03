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

    public function __construct($userAuthKey, $appIDKey = null, $restAPIKey = null, $options = [])
    {
        $this->apiBaseURI = 'https://onesignal.com/api/v1/';
        $this->userAuthKey = $userAuthKey;
        $this->appIDKey = $appIDKey;
        $this->restAPIKey = $restAPIKey;

        $options = $this->getDefaultOptions($options);
        $this->guzzleClient = new Client(array_merge(
            [
                'base_uri' => $this->apiBaseURI
            ],
            $options
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

    public function execute($url, $method, array $options = [])
    {
        $defaultOptions = [
            'headers' => [
                'Authorization' => sprintf('Basic %s', $this->userAuthKey),
            ],
            'form_params' => [],
        ];
        $endpointURL = sprintf('%s%s', $this->apiBaseURI, $url);
        $options = array_merge_recursive($defaultOptions, $options);

        $response = $this->guzzleClient->request(strtoupper($method), $endpointURL, $options);

        return json_decode($response->getBody()->getContents());
    }

    private function getDefaultOptions(array $options)
    {
        $defaultOptions = [
            'timeout' => 30,
        ];

        foreach ($defaultOptions as $option => $value) {
            if (isset($options[$option])) {
                $defaultOptions[$option] = $options[$option];
            }
        }

        return $defaultOptions;
    }
}
