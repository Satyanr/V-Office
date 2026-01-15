<?php

namespace App\Jobs;

use App\Models\ProjectTbl;
use App\Mail\ReminderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable; 
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendProjectReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $projectId) {}

    public function handle(): void
    {
        $project = ProjectTbl::with('konsumen')->findOrFail($this->projectId);

        if (!$project->konsumen?->email) {
            return;
        }

        $body = "
        Yth. {$project->konsumen->nama},

        Ini adalah pengingat terkait project:
        Kode Project: {$project->kode_project}

        Keterangan:
        {$project->keterangan}

        Terima kasih.
        ";

        Mail::to($project->konsumen->email)
            ->send(new ReminderMail(
                subjectText: 'Pengingat Project',
                bodyText: $body
            ));
    }
}
