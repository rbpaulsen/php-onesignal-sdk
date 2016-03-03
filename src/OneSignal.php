<?php

namespace NNV\OneSignal;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use Exception;

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

    /**
     * @param string $userAuthKey User auth key
     * @param string $appIDKey    App ID key
     * @param string $restAPIKey  REST API key
     * @param array  $options     Extra options for GuzzleHttp Client
     */
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

    /**
     * Get API base URI
     *
     * @return string API base URI
     */
    public function getAPIBaseURI()
    {
        return $this->apiBaseURI;
    }

    /**
     * Get user auth key
     *
     * @return string User auth key
     */
    public function getUserAuth()
    {
        return $this->userAuthKey;
    }

    /**
     * Set app ID key
     *
     * @param string $appIDKey App ID key
     * @return self
     */
    public function setAppIDKey($appIDKey)
    {
        $this->appIDKey = $appIDKey;

        return $this;
    }

    /**
     * Get App ID key
     *
     * @return string App ID key
     */
    public function getAppIDKey()
    {
        return $this->appIDKey;
    }

    /**
     * Set REST API key
     *
     * @param string $restAPIKey REST API key
     * @return self
     */
    public function setRESTAPIKey($restAPIKey)
    {
        $this->restAPIKey = $restAPIKey;

        return $this;
    }

    /**
     * Execute call API
     *
     * @param  string $url     URL to call
     * @param  string $method  Method to call
     * @param  array  $options Extra options for GuzzleHttp Client
     * @return object Object contain response detail
     */
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
        $response = [];

        try {
            $response = $this->guzzleClient->request(strtoupper($method), $endpointURL, $options);
        } catch (ClientException $clientEx) {
            $response = $clientEx->getResponse();
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return $this->getResponseContent($response);
    }

    /**
     * Get detail response
     *
     * @param  Response $response Response from GuzzleHttp Client
     * @return object Response detail
     */
    public function getResponseContent(Response $response)
    {
        $statusCode = $response->getStatusCode();
        return (object) [
            'status' => ($statusCode === 200),
            'code' => $statusCode,
            'response' => json_decode($response->getBody()->getContents()),
        ];
    }

    /**
     * Get default options for GuzzleHttp Client
     *
     * @param  array  $options Extra options for GuzzleHttp Client
     * @return array  GuzzleHttp Client options
     */
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
