<?php

namespace App\Console\Commands;

use App\Models\ProjectTbl;
use Illuminate\Console\Command;
use App\Jobs\SendProjectReminderJob;

class DispatchProjectReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:dispatch-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch project reminder emails based on tanggal_pengingat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->startOfDay();

        $projects = ProjectTbl::with('konsumen')
            ->whereDate('tanggal_pengingat', $today->copy()->addDays(7))
            ->get();

        foreach ($projects as $project) {
            if (!$project->konsumen?->email) {
                continue;
            }

            SendProjectReminderJob::dispatch($project->id);
        }

        $this->info("Dispatched: {$projects->count()} reminders");
    }
}
