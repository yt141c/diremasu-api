<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshAndSeed extends Command
{
    protected $signature = 'db:refseed';
    protected $description = 'Run the database refresh and seed';

    public function handle()
    {
        $this->call('migrate:refresh');
        $this->call('db:seed');
    }
}
