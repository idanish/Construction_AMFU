<?php

namespace App\Http\Controllers;
use App\Models\Setting;
use Illuminate\Http\Request;

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

    $file = $request->file('backup_file');
    $path = $file->getRealPath();

    $dbHost = env('DB_HOST', '127.0.0.1');
    $dbPort = env('DB_PORT', '3306');
    $dbUser = env('DB_USERNAME', 'root');
    $dbPass = env('DB_PASSWORD', '');
    $dbName = env('DB_DATABASE', 'amfuS');

    // XAMPP MySQL path (apne system ka check kar lena)
    $mysqlPath = "C:/xampp/mysql/bin/mysql.exe";

    $command = "\"$mysqlPath\" -h$dbHost -P$dbPort -u$dbUser " . 
               ($dbPass ? "-p$dbPass " : "") . 
               "$dbName < \"$path\"";

    $output = null;
    $returnVar = null;
    exec($command, $output, $returnVar);

    if ($returnVar === 0) {
        return back()->with('success', 'Database restore successfully.');
    } else {
        return back()->with('error', 'Failed to restore database.');
    }
}

//logo function

 public function showLogoForm()
    {
        $setting = Setting::first(); // database se setting fetch
        return view('settings.logo', compact('setting')); // pass variable to blade
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
            // $setting->site_name = "My Website"; // default
        }

        $setting->logo = $path;
        $setting->save();

        return back()->with('success', '✅ Logo updated successfully!');
    }
}