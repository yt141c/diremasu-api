<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Factory;

class FbsampleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fb:sample';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $factory = (new Factory)
            ->withServiceAccount(config('firebase.projects.diremasu.credentials'))
            ->withDatabaseUri('https://flutter_memo_api.firebaseio.com');

        $auth = $factory->createAuth();
    }
}
