<?php

namespace App\Console\Commands;

use App\Models\Student;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResetWeeklyStudentStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:reset-weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset choix and consigned f debut tae smana ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting weekly student status reset...');

        try {
            $count = Student::query()->update([
                'choix' => null,
                'consigned' => 0,
            ]);

            $message = "Successfully reset {$count} students: choix = null, consigned = 0";
            $this->info($message);
            Log::info($message);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $error = 'Failed to reset student status: ' . $e->getMessage();
            $this->error($error);
            Log::error($error);

            return Command::FAILURE;
        }
    }
}
