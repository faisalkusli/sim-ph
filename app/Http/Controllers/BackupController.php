<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ifsnop\Mysqldump as IMysqldump;
use Exception;

class BackupController extends Controller
{
    public function index()
    {
        return view('admin.backup.index');
    }

    public function backup()
    {
        try {
            $filename = "backup-eoffice-" . date('Y-m-d_H-i-s') . ".sql";
            $path = storage_path("app/" . $filename);
            $dbName = env('DB_DATABASE');
            $username = env('DB_USERNAME');
            $password = env('DB_PASSWORD');
            $host = env('DB_HOST');
            $dumpSettings = [
                'compress' => 'None',
                'no-data' => false,
                'add-drop-table' => true,
                'single-transaction' => true,
                'lock-tables' => true,
                'add-locks' => true
            ];

            $dump = new IMysqldump\Mysqldump(
                "mysql:host={$host};dbname={$dbName}", 
                $username, 
                $password, 
                $dumpSettings
            );
            
            $dump->start($path);

            return response()->download($path)->deleteFileAfterSend(true);

        } catch (Exception $e) {
            return back()->with('error', 'Gagal backup: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        $request->validate([
            'file_sql' => 'required|file|mimes:sql,txt'
        ]);

        try {
            $sqlContent = file_get_contents($request->file('file_sql')->getRealPath());

            DB::unprepared($sqlContent);

            return back()->with('success', 'Database BERHASIL direstore! Data kembali seperti semula.');

        } catch (Exception $e) {
            return back()->with('error', 'Gagal restore: ' . $e->getMessage());
        }
    }
}