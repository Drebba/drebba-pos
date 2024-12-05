<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class BackupBusinessData extends Command
{
    protected $signature = 'backup:business {businessId}';
    protected $description = 'Backup data for a specific business';

    // Excluded tables from the backup
    protected $excludedTables = ['admins', 'businesses'];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

    }
}
