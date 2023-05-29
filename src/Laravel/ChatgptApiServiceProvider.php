<?php

namespace Bnoordsij\ChatgptApi\Laravel;

use Bnoordsij\ChatgptApi\Api\Client;
use Bnoordsij\ChatgptApi\Api\Endpoint;
use Bnoordsij\ChatgptApi\Contracts\Api\Client as ClientContract;
use Bnoordsij\ChatgptApi\Contracts\Api\Endpoint as EndpointContract;
use Illuminate\Support\ServiceProvider;

class ChatgptApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/chatgpt-api.php' => config_path('chatgpt-api.php'),
        ], 'chatgpt-api');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/chatgpt-api.php', 'chatgpt-api');

        $this->app->singleton(ClientContract::class, function (): Client {
            $config = config('chatgpt-api.chatgpt');

            return new Client(
                $config['base_url'],
                $config['api_key'],
                $config['model'],
            );
        });

        $this->app->singleton(EndpointContract::class, function (): Endpoint {
           return new Endpoint($this->app->make(ClientContract::class));
        });
    }
}
