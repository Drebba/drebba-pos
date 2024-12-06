<?php

namespace App\Http\Controllers;

use App\Exports\DatabaseExport;
use App\Jobs\UpdateBackupStatus;
use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Toastr;
use Illuminate\Support\Str;
class BackupController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('manage_backup')) {
            abort(403);
        }
        $backups = Backup::where('business_id', Auth::user()->business_id)->latest()->paginate(10);
        return view('backup', compact('backups'));
    }

    public function store()
    {
        if (!Auth::user()->can('manage_backup')) {
            abort(403);
        }



        $business_id = Auth::user()->business_id; // Get business_id from request
        $fileName = 'backups/database_export_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        // Check if there are more than 2 backups created in the last hour
        $backupCountLastHour = Backup::where('business_id', $business_id)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        if ($backupCountLastHour >= 2) {
            Toastr::error('You can only create 2 backups per hour.', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
            return redirect()->back();
        }

        $backup = new Backup();
        $backup->business_id = Auth::user()->business_id;
        $backup->start_at = now();
        $backup->status = 0;
        $backup->uuid = Str::uuid();
        $backup->path = $fileName;
        $backup->save();

        // Queue the export
        (new DatabaseExport($business_id))->queue($fileName, 'local')->chain([
            new UpdateBackupStatus($backup->id),
        ]);
        Toastr::success('Data Export started', '', ['progressBar' => true, 'closeButton' => true, 'positionClass' => 'toast-bottom-right']);
        return redirect()->back();
    }

    public function download($uuid)
{
    // Check if the user has the required permission
    if (!Auth::user()->can('manage_backup')) {
        abort(403, 'You are not authorized to access this resource.');
    }

    // Fetch the backup record by UUID
    $backup = Backup::where('uuid', $uuid)->where('business_id',Auth::user()->business_id)->first();

    // Check if the backup exists
    if (!$backup) {
        abort(404, 'Backup file not found.');
    }

    // Check if the file exists on the filesystem
    $file = storage_path("app/{$backup->path}");
    if (!file_exists($file)) {
        abort(404, 'Backup file does not exist on the server.');
    }

    // Return the file for download
    return response()->download($file, basename($file));
}

}
