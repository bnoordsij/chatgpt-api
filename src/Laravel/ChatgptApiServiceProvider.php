<?php

namespace Bnoordsij\ChatgptApi\Laravel;

use Illuminate\Support\ServiceProvider;

class ChatgptApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->scoped(ClientContract::class, function (): Client {
            $config = config('services.chatgpt');
            return new Client(
                $config['base_url'],
                $config['key'],
                $config['model'],
            );
        });

    }
}
