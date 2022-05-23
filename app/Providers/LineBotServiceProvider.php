<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use LINE\LINEBot;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;

class LineBotServiceProvider extends ServiceProvider
{
    /**
     * Register Line bot
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(LINEBot::class, function(){
            $channelToken = env('LINE_CHANNEL_ACCESS_TOKEN');
            $channelSecret = env('LINE_CHANNEL_SECRET');
            return new LINEBot(new CurlHTTPClient($channelToken), ['channelSecret' => $channelSecret]);
        });
    }
}