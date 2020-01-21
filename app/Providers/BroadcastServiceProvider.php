<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Broadcast::routes(['middleware' => ['auth:api']]);//isi tambah by wahyono
        Broadcast::routes(['middleware' => ['web']]);
        //Broadcast::routes(); //ini originalnya
        require base_path('routes/channels.php');
    }
}
