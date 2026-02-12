<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DatabaseBackup extends Command
{
    protected $signature = 'db:backup {--manual : Manual backup from admin} {--drive= : Drive letter (e.g. F)}';

    protected $description = 'Backup database to USB drive (Windows only)';

    public function handle()
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            $this->error('This command only works on Windows');
            Log::error('Database backup failed: Not running on Windows');
            return 1;
        }

        $driveLetter = $this->option('drive');

        if ($driveLetter) {
            $usbDrive = strtoupper($driveLetter) . ':\\';
            if (!is_dir($usbDrive)) {
                $this->error("Drive {$driveLetter}: not found");
                Log::error("Database backup failed: Drive {$driveLetter}: not accessible");
                return 1;
            }
        } else {
            $usbDrive = $this->findUsbDrive();
        }

        if (!$usbDrive) {
            $this->error('No USB drive found. Use --drive=F to specify drive letter');
            Log::error('Database backup failed: No USB drive connected');
            return 1;
        }

        $this->info("USB drive found: {$usbDrive}");

        $backupFolder = $usbDrive . 'TajSultan_Backups';
        if (!is_dir($backupFolder)) {
            mkdir($backupFolder, 0755, true);
        }

        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port', 3306);

        $timestamp = date('Y-m-d_H-i-s');
        $filename = "backup_{$database}_{$timestamp}.sql";
        $filepath = $backupFolder . DIRECTORY_SEPARATOR . $filename;

        $mysqldumpPath = $this->findMysqldump();

        if (!$mysqldumpPath) {
            $this->error('mysqldump not found');
            Log::error('Database backup failed: mysqldump not found');
            return 1;
        }

        $command = sprintf(
            '"%s" --host=%s --port=%s --user=%s --password=%s %s > "%s"',
            $mysqldumpPath,
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database),
            $filepath
        );

        $this->info('Creating backup...');

        exec($command . ' 2>&1', $output, $returnCode);

        if ($returnCode !== 0) {
            $this->error('Backup failed');
            Log::error('Database backup failed: ' . implode("\n", $output));
            return 1;
        }

        if (!file_exists($filepath) || filesize($filepath) === 0) {
            $this->error('Backup file is empty or not created');
            Log::error('Database backup failed: File empty or not created');
            return 1;
        }

        $size = $this->formatBytes(filesize($filepath));
        $this->info("Backup created successfully!");
        $this->info("File: {$filepath}");
        $this->info("Size: {$size}");

        Log::info("Database backup created: {$filepath} ({$size})");

        $this->cleanOldBackups($backupFolder, 30);

        return 0;
    }

    private function findUsbDrive(): ?string
    {
        $drives = [];

        for ($letter = 'D'; $letter <= 'Z'; $letter++) {
            $drive = $letter . ':\\';
            if (is_dir($drive)) {
                $driveType = $this->getDriveType($drive);
                if ($driveType === 'removable' || $driveType === 'fixed') {
                    $drives[] = $drive;
                }
            }
        }

        return $drives[0] ?? null;
    }

    private function getDriveType(string $drive): string
    {
        $letter = substr($drive, 0, 1);
        $script = 'Get-WmiObject Win32_LogicalDisk | Where-Object {$_.DeviceID -eq "' . $letter . ':"} | Select-Object -ExpandProperty DriveType';

        exec("powershell -Command \"{$script}\" 2>&1", $output, $returnCode);

        if ($returnCode === 0 && !empty($output)) {
            $type = trim($output[0]);
            if ($type === '2') {
                return 'removable';
            }
            if ($type === '3') {
                return 'fixed';
            }
        }

        return 'unknown';
    }

    private function findMysqldump(): ?string
    {
        $commonPaths = [
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\wamp64\\bin\\mysql\\mysql8.0.31\\bin\\mysqldump.exe',
            'C:\\wamp\\bin\\mysql\\mysql5.7.36\\bin\\mysqldump.exe',
            'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 5.7\\bin\\mysqldump.exe',
        ];

        foreach ($commonPaths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        exec('where mysqldump 2>&1', $output, $returnCode);
        if ($returnCode === 0 && !empty($output)) {
            return trim($output[0]);
        }

        return null;
    }

    private function cleanOldBackups(string $folder, int $daysToKeep): void
    {
        $files = glob($folder . DIRECTORY_SEPARATOR . 'backup_*.sql');
        $cutoff = time() - ($daysToKeep * 24 * 60 * 60);

        foreach ($files as $file) {
            if (filemtime($file) < $cutoff) {
                unlink($file);
                $this->info("Deleted old backup: " . basename($file));
            }
        }
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
