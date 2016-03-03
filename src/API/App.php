<?php

namespace NNV\OneSignal\API;

use NNV\OneSignal\OneSignal;

class App
{
    private $oneSignal;

    public function __construct(OneSignal $oneSignal)
    {
        $this->oneSignal = $oneSignal;
    }

    public function get()
    {
        $apps = $this->oneSignal->execute('apps', 'GET');

        return $apps;
    }

    public function find($id)
    {
        $url = sprintf('apps/%s', $id);
        $app = $this->oneSignal->execute($url, 'GET');

        return $app;
    }
}
