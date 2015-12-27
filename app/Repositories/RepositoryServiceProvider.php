<?php

namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider {

    protected $eloquentRepositories = [
        'User',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->eloquentRepositories as $repo) {
            $interface = __NAMESPACE__ . '\\Contracts\\' . $repo . 'RepositoryInterface';
            $repository = __NAMESPACE__ . '\\Eloquent\\' . $repo . 'Repository';
            $this->app->bind($interface, $repository);
        }
    }


}