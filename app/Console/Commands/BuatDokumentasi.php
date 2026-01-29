<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use ReflectionClass;

class BuatDokumentasi extends Command
{
    /**
     * Nama dan signature command console.
     *
     * @var string
     */
    protected $signature = 'dokumentasi:generate';

    /**
     * Deskripsi command console.
     *
     * @var string
     */
    protected $description = 'Menghasilkan file dokumentasi FITUR_SISTEM.md secara otomatis berdasarkan kode yang ada.';

    /**
     * Jalankan command console.
     */
    public function handle()
    {
        $header = "# RANGKUMAN FITUR & FUNGSI SISTEM PUSKESMAS JAGAKARSA\n\n";
        $header .= "Status Dokumen: **TERGENERASI OTOMATIS**\n";
        $header .= "Terakhir Diupdate: " . now()->format('d F Y H:i:s') . "\n\n";
        
        // 1. Statistik
        $totalModels = count(glob(app_path('Models/*.php')));
        
        // Scan Livewire recursively
        $livewireFiles = $this->getAllFiles(app_path('Livewire'));
        $totalLivewire = count($livewireFiles);

        $totalMigrations = count(glob(database_path('migrations/*.php')));

        $content = "## 1. Statistik Sistem\n";
        $content .= "- **Total Model Database:** " . $totalModels . "\n";
        $content .= "- **Total Komponen Livewire:** " . $totalLivewire . "\n";
        $content .= "- **Total Migrasi Database:** " . $totalMigrations . "\n\n";

        // 2. Struktur Database
        $content .= "## 2. Struktur Database (Tabel)\n";
        $migrations = glob(database_path('migrations/*.php'));
        foreach ($migrations as $migration) {
            $name = basename($migration, '.php');
            $parts = explode('_', $name);
            // Hapus timestamp (YYYY_MM_DD_HIS)
            array_shift($parts); array_shift($parts); array_shift($parts); array_shift($parts);
            $tableName = implode(' ', $parts);
            $content .= "- " . ucwords($tableName) . " (`$name`)\n";
        }
        $content .= "\n";

        // 3. Model & Relasi (Simple inspection)
        $content .= "## 3. Model Data\n";
        $models = glob(app_path('Models/*.php'));
        foreach ($models as $modelPath) {
            $modelName = basename($modelPath, '.php');
            $content .= "- **$modelName**\n";
        }
        $content .= "\n";

        // 4. Peta Rute
        $content .= "## 4. Peta Rute & Halaman\n";
        $routes = Route::getRoutes();
        foreach ($routes as $route) {
            if (str_contains($route->uri(), '_ignition') || str_contains($route->uri(), 'sanctum')) continue;
            
            $methods = implode('|', $route->methods());
            $uri = $route->uri();
            $action = $route->getActionName();
            if ($action === 'Closure') $action = 'Fungsi Langsung';
            
            $content .= "- **$methods** `/$uri` -> `$action`\n";
        }
        $content .= "\n";

        // 5. Modul Livewire (Recursive)
        $content .= "## 5. Modul Utama (Livewire)\n";
        foreach ($livewireFiles as $file) {
            $relativePath = str_replace(app_path('Livewire') . DIRECTORY_SEPARATOR, '', $file);
            $className = str_replace(['.php', DIRECTORY_SEPARATOR], ['', '\\'], $relativePath);
            $content .= "- **$className**: Komponen interaktif SPA.\n";
        }

        $this->info('Menulis file FITUR_SISTEM.md...');
        file_put_contents(base_path('FITUR_SISTEM.md'), $header . $content);
        $this->info('Dokumentasi berhasil diperbarui!');
    }

    private function getAllFiles($dir) {
        $files = [];
        if (!is_dir($dir)) return $files;
        
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }
        return $files;
    }
}
