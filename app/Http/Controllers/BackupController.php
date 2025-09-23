<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupController extends Controller
{
    public function index(){
        return view('settings.backup&restore');
    }
    public function backupDatabase()
    {
        // Credentials ko .env file se load karein, jo zyadah secure hai.
        $dbName = config('database.connections.mysql.database');
        $user = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');

        // Path ko bhi dynamic rakhein.
        $mysqldumpPath = 'C:/xampp/mysql/bin/mysqldump.exe'; // Aapka hardcoded path
        
        $backupFile = storage_path('app/backups/' . $dbName . '_' . date('Y-m-d_H-i-s') . '.sql');

        // Backup command
        $command = "\"$mysqldumpPath\" --user=$user --password=$password --host=$host $dbName > $backupFile";

        try {
            // Process component ka istemal karein jo zyadah behtar hai
            $process = Process::fromShellCommandline($command);
            $process->run();

            // Agar command fail ho to exception throw karein
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // File download aur deletion
            return response()->download($backupFile)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            // Error handling
            return redirect()->back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }
}