<?php

namespace App\Services;

use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider {

    protected $services = [
        'Article',
        'User',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->services as $service) {
            $interface = __NAMESPACE__ . '\\Contracts\\' . $service . 'ServiceInterface';
            $contract = __NAMESPACE__ . '\\' . $service . 'Service';
            $this->app->bind($interface, $contract);
        }
    }


}