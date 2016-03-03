<?php

namespace NNV\OneSignal\API;

use NNV\OneSignal\OneSignal;

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
    public function get()
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
    public function find($id)
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
        $url = sprintf('apps/%s', $appId);
        $app = $this->oneSignal->execute($url, 'PUT', [
            'form_params' => $appData,
        ]);

        return $app;
    }
}
