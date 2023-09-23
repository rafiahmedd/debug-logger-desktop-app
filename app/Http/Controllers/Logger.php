<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Logger as LoggerModel;
use Native\Laravel\Dialog;
use Native\Laravel\Facades\Notification;

class Logger extends Controller
{
    public function fetchAllLogs()
    {
        return LoggerModel::get();
    }

    public function addLogToApp(Request $req)
    {
        $filePath = Dialog::new()
            ->title('Select log file')
            ->open();

        if (empty($filePath)) return false;

        $addedLog = LoggerModel::create([
            'project' => 'Default',
            'file_path' => $filePath
        ]);

        if ($addedLog) {
            Notification::title('Hello Rafi')
                ->message('Log added successfully!')
                ->show();

            return [
                'done' => true
            ];
        }
    }

    public function fetchLog($id)
    {
        $log = LoggerModel::findOrFail($id);
        return file_get_contents($log->file_path);
    }

    public function deleteLog($id)
    {
        $log = LoggerModel::findOrFail($id);
        $log->delete();

        Notification::title('Hello Rafi')
            ->message('Log removed successfully!')
            ->show();

        return true;
    }

    public function monitorFile(Request $request)
    {
        Notification::title('Hello Rafi')
            ->message("New logs added in {$request->get('file_path')}")
            ->show();
            
        return file_get_contents($request->get('file_path'));

        // $logs = LoggerModel::all();

        // $logs->each(function ($log) {
        //     $lastModifiedAt = filemtime($log->file_path);
        //     $updatedAt = $log->updated_at->getTimestamp();

        //     if ($lastModifiedAt > $updatedAt) {
        //         // Read the file contents into an array
        //         $file_contents = file($log->file_path, FILE_IGNORE_NEW_LINES);

        //         // Process the new item, for example, you can access the last item added
        //         $newItem = end($file_contents);

        //         LoggerModel::where('id', $log->id)->update([
        //             'updated_at' => date('Y-m-d H:i:s', $lastModifiedAt)
        //         ]);

        //         if ($newItem) {

        //         }
        //     } else {
        //         // Notification::title('Hello Rafi')
        //         //     ->message("No changes yet! {$log->file_path}")
        //         //     ->show();
        //     }
        // });
    }

    public function clearLogFile(Request $request)
    {
        file_put_contents($request->get('file_path'), '');

        Notification::title('Hello Rafi')
            ->message("Log cleared successfully.")
            ->show();
    }
}
