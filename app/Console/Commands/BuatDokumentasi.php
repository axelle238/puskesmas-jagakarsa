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
        
        $content = "## 1. Statistik Sistem\n";
        $content .= "- **Total Model Database:** " . count(glob(app_path('Models/*.php'))) . "\n";
        $content .= "- **Total Komponen Livewire:** " . count(glob(app_path('Livewire/*.php'))) . "\n";
        $content .= "- **Total Migrasi Database:** " . count(glob(database_path('migrations/*.php'))) . "\n\n";

        $content .= "## 2. Struktur Database (Tabel)\n";
        // Simulasi pembacaan nama file migrasi untuk daftar tabel
        $migrations = glob(database_path('migrations/*.php'));
        foreach ($migrations as $migration) {
            $name = basename($migration, '.php');
            // Ambil nama tabel dari nama file (asumsi format standar)
            $parts = explode('_', $name);
            // Hapus timestamp
            array_shift($parts); array_shift($parts); array_shift($parts); array_shift($parts);
            $tableName = implode(' ', $parts);
            $content .= "- " . ucwords($tableName) . " (`$name`)\n";
        }
        $content .= "\n";

        $content .= "## 3. Peta Rute & Halaman\n";
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

        $content .= "## 4. Modul Utama (Livewire)\n";
        $components = glob(app_path('Livewire/*.php'));
        foreach ($components as $component) {
            $className = basename($component, '.php');
            $content .= "- **$className**: Komponen interaktif SPA.\n";
        }

        $this->info('Menulis file FITUR_SISTEM.md...');
        file_put_contents(base_path('FITUR_SISTEM.md'), $header . $content);
        $this->info('Dokumentasi berhasil diperbarui!');
    }
}
