<?php

namespace App\Listeners;

use App\Events\SortieLocked;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LockingSortie implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $retryAfter = 5;

    /**
     * The number of times the job should be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SortieLocked $event): void
    {
        Log::info("Locking sortie for bat: {$event->bat}");
        DB::update("UPDATE list_lock set status=1 WHERE id=?", [$event->bat]);
    }
}
