<?php

namespace App\Jobs;

use App\Models\Backup;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateBackupStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $backupId;

    public function __construct($backupId)
    {
        $this->backupId = $backupId;
    }

    public function handle()
    {
        $backup = Backup::find($this->backupId);
        if ($backup) {
            $backup->status = 1;
            $backup->completed_at = now();
            $backup->save();
        }
    }
}
