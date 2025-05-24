<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UnlockList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unlock-list {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "unlocks the given batallion's list";

    /**
     * Execute the console command.
     */
    public function handle()

    {
        $bat = $this->argument("id");
        DB::update("UPDATE list_lock set status=0 where id=?", [$bat]);
    }
}
