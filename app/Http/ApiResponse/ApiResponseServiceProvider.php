<?php

namespace App\Http\ApiResponse;

use Illuminate\Support\ServiceProvider;
use App\Http\ApiResponse\Contracts\ApiResponseInterface;
use App\Http\ApiResponse\ApiResponse;

class ApiResponseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ApiResponseInterface::class, ApiResponse::class);
   }

}