<?php

namespace NNV\OneSignal\API;

use NNV\OneSignal\OneSignal;
use NNV\OneSignal\Utils\Validation;

class App
{
    /**
     * OneSignal instance
     *
     * @var NNV\OneSignal\OneSignal
     */
    private $oneSignal;

    /**
     * @param NNV\OneSignal\OneSignal $oneSignal OneSignal instance
     */
    public function __construct(OneSignal $oneSignal)
    {
        $this->oneSignal = $oneSignal;
    }

    /**
     * Get all apps
     *
     * @return NNV\OneSignal\OneSignal::execute()
     */
    public function all()
    {
        $apps = $this->oneSignal->execute('apps', 'GET');

        return $apps;
    }

    /**
     * Find app by App ID
     *
     * @param  string $id App ID
     * @return NNV\OneSignal\OneSignal::execute()
     */
    public function get($id)
    {
        $url = sprintf('apps/%s', $id);
        $app = $this->oneSignal->execute($url, 'GET');

        return $app;
    }

    /**
     * Create app
     *
     * @param  array  $appData App information
     * @return NNV\OneSignal\OneSignal::execute()
     */
    public function create(array $appData)
    {
        $this->validateAppData($appData, $this->getAppDataRules());

        $app = $this->oneSignal->execute('apps', 'POST', [
            'form_params' => $appData,
        ]);

        return $app;
    }

    /**
     * Update an app
     *
     * @param  string $appId   App ID
     * @param  array  $appData App data to update
     * @return NNV\OneSignal\OneSignal::execute()
     */
    public function update($appId, array $appData)
    {
        $appDataRules = $this->getAppDataRules();
        unset($appDataRules['required'][0]);

        $this->validateAppData($appData, $appDataRules);

        $url = sprintf('apps/%s', $appId);
        $app = $this->oneSignal->execute($url, 'PUT', [
            'form_params' => $appData,
        ]);

        return $app;
    }

    /**
     * Application data validation rules
     *
     * @return array Validation rules
     */
    private function getAppDataRules()
    {
        return [
            'required' => ['name'],
            'defined' => [
                'name' => 'string',
                'apns_env' => [
                    'allowedTypes' => 'string',
                    'allowedValues' => ['sandbox', 'production'],
                ],
                'apns_p12' => 'string',
                'apns_p12_password' => 'string',
                'gcm_key' => 'string',
                'android_gcm_sender_id' => 'string',
                'chrome_web_origin' => 'string',
                'chrome_web_origin' => 'string',
                'chrome_web_default_notification_icon' => 'string',
                'chrome_web_sub_domain' => 'string',
                'safari_apns_p12' => 'string',
                'safari_apns_p12_password' => 'string',
                'site_name' => 'string',
                'safari_site_origin' => 'string',
                'safari_icon_256_256' => 'string',
                'chrome_key' => 'string',
            ],
        ];
    }

    /**
     * Validate application data
     *
     * @param  array  $appData Application data
     * @param  array  $validateRules Application validation rules
     * @throws mixed NNV\OneSignal\Utils\Validation::validate or null
     */
    private function validateAppData(array $appData, array $validateRules)
    {
        $validation = new Validation;

        $validation->setMultiRequired($validateRules['required'])
                   ->setMultiDefined($validateRules['defined'])
                   ->validate($appData);
    }
}
