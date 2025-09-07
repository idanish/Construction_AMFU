<?php

namespace App\Http\Controllers;
use App\Models\Setting;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;


class SettingsController extends Controller
{
   public function index() {
        return view('settings.backup&restore');
    }

    public function security()
    {
        return view('settings.setting');
    }

   public function backupDatabase()
{
    $dbHost     = env('DB_HOST', '127.0.0.1');
    $dbUser     = env('DB_USERNAME', 'root');
    $dbPassword = env('DB_PASSWORD', '');
    $dbName     = env('DB_DATABASE', 'amfu');

    $fileName = $dbName . '_backup_' . date('Y-m-d_H-i-s') . '.sql';
    $filePath = storage_path("app/backups/$fileName");

    if (!file_exists(storage_path("app/backups"))) {
        mkdir(storage_path("app/backups"), 0777, true);
    }

    $dumpPath = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';

    $command = "\"$dumpPath\" -h $dbHost -u $dbUser " 
             . ($dbPassword ? "-p$dbPassword " : "") 
             . "$dbName > \"$filePath\"";

    system($command);

    if (file_exists($filePath)) {
        return response()->download($filePath)->deleteFileAfterSend(true);
    } else {
        return back()->with('error', '⚠ Backup failed. File not created.');
    }
}  

//restore function

public function restoreBackup(Request $request)
{
    $request->validate([
        'backup_file' => 'required|file|mimes:sql,txt',
    ]);

    // File ko storage me save karo
    $storedPath = $request->file('backup_file')->store('backups');
    $path = storage_path('app/' . $storedPath);

    try {
        // File ka content read karo
        $sql = file_get_contents($path);

        // Queries ko run karo
        DB::unprepared($sql);

        return back()->with('success', '✅ Database restored successfully!');
    } catch (\Exception $e) {
        return back()->with('error', '❌ Restore failed: ' . $e->getMessage());
    }
}


//logo function

 public function showLogoForm()
    {
        $setting = Setting::first();
        return view('settings.logo', compact('setting'));
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $path = $request->file('logo')->store('logos', 'public');

        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
            
        }

        $setting->logo = $path;
        $setting->save();

        return back()->with('success', '✅ Logo updated successfully!');
    }
}