<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function backupDatabase()
    {
        $dbName = 'filemanager';
        $user = 'root';
        $password = '';
        $host = '127.0.0.1';
        $path = 'C:/xampp/mysql/bin/mysqldump.exe';

        $backupFile = $dbName . '_backup1_' . date('Y-m-d_H-i-s') . '.sql';

        $command = "\"$path\" --user=$user --password=$password --host=$host $dbName > $backupFile";

        system($command);

        return response()->download($backupFile)->deleteFileAfterSend(true);
    }
}
