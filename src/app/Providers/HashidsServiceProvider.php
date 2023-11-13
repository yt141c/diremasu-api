<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Hashids\Hashids;

class HashidsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->singleton('hashids', function ($app) {
            // ここでsaltとlengthを設定します。これらは.envファイルから取得するのが良いでしょう。
            $salt = config('app.hashids_salt');
            $length = config('app.hashids_length', 10);
            return new Hashids($salt, $length);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
