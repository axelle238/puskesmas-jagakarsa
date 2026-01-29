<?php

namespace App\Livewire\Pengaturan;

use App\Models\LogBackup;
use App\Models\LogKeamanan;
use App\Models\PengaturanKeamanan;
use App\Models\WhitelistIp;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Class ManajemenIT
 * Pusat kontrol keamanan siber dan konfigurasi teknis sistem.
 */
class ManajemenIT extends Component
{
    use WithPagination;

    public $activeTab = 'dashboard'; // dashboard, ip, logs, backup, settings

    // Form Whitelist IP
    public $ip_address;
    public $keterangan_ip;
    public $idIpDiedit;
    public $modalIp = false;

    // Form Pengaturan
    public $batasi_ip_login_admin;
    public $maksimal_percobaan_login;
    public $durasi_blokir_menit;
    public $wajib_ganti_password_berkala;
    
    // Filter Log
    public $cariLog = '';

    public function mount()
    {
        $this->loadPengaturan();
    }

    public function loadPengaturan()
    {
        $settings = PengaturanKeamanan::first();
        if (!$settings) {
            $settings = PengaturanKeamanan::create([]);
        }

        $this->batasi_ip_login_admin = (bool) $settings->batasi_ip_login_admin;
        $this->maksimal_percobaan_login = $settings->maksimal_percobaan_login;
        $this->durasi_blokir_menit = $settings->durasi_blokir_menit;
        $this->wajib_ganti_password_berkala = (bool) $settings->wajib_ganti_password_berkala;
    }

    public function simpanPengaturan()
    {
        $settings = PengaturanKeamanan::first();
        $settings->update([
            'batasi_ip_login_admin' => $this->batasi_ip_login_admin,
            'maksimal_percobaan_login' => $this->maksimal_percobaan_login,
            'durasi_blokir_menit' => $this->durasi_blokir_menit,
            'wajib_ganti_password_berkala' => $this->wajib_ganti_password_berkala,
        ]);

        session()->flash('sukses_settings', 'Konfigurasi keamanan berhasil diperbarui.');
    }

    // --- WHITELIST IP ---

    public function tambahIp()
    {
        $this->resetIpForm();
        $this->modalIp = true;
    }

    public function editIp($id)
    {
        $ip = WhitelistIp::find($id);
        $this->idIpDiedit = $id;
        $this->ip_address = $ip->ip_address;
        $this->keterangan_ip = $ip->keterangan;
        $this->modalIp = true;
    }

    public function simpanIp()
    {
        $this->validate([
            'ip_address' => 'required|ip',
            'keterangan_ip' => 'required|string',
        ]);

        $data = [
            'ip_address' => $this->ip_address,
            'keterangan' => $this->keterangan_ip,
            'aktif' => true
        ];

        if ($this->idIpDiedit) {
            WhitelistIp::find($this->idIpDiedit)->update($data);
        } else {
            WhitelistIp::create($data);
        }

        $this->modalIp = false;
        $this->resetIpForm();
        session()->flash('sukses_ip', 'Data IP berhasil disimpan.');
    }

    public function hapusIp($id)
    {
        WhitelistIp::find($id)->delete();
        session()->flash('sukses_ip', 'IP dihapus dari whitelist.');
    }

    public function resetIpForm()
    {
        $this->reset(['ip_address', 'keterangan_ip', 'idIpDiedit']);
    }

    // --- BACKUP ---

    public function triggerBackup()
    {
        // Simulasi proses backup database
        // Di environment nyata, ini akan menjalankan `php artisan backup:run` via Process
        
        $filename = 'backup-puskesmas-' . date('Y-m-d-His') . '.zip';
        
        LogBackup::create([
            'nama_file' => $filename,
            'path_penyimpanan' => 'backups/' . $filename,
            'ukuran_file' => rand(10, 50) . ' MB', // Simulasi
            'status' => 'sukses',
            'id_pembuat' => auth()->id()
        ]);

        session()->flash('sukses_backup', 'Backup sistem berhasil dijalankan (Simulasi).');
    }

    public function render()
    {
        $whitelist = WhitelistIp::all();
        
        $logs = LogKeamanan::where('event', 'like', '%' . $this->cariLog . '%')
            ->orWhere('ip_address', 'like', '%' . $this->cariLog . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $backups = LogBackup::with('pembuat')->orderBy('created_at', 'desc')->get();

        // Statistik Dashboard IT
        $stats = [
            'total_serangan' => LogKeamanan::where('event', 'Brute Force')->count(),
            'login_gagal_hari_ini' => LogKeamanan::where('event', 'Login Gagal')->whereDate('created_at', today())->count(),
            'backup_terakhir' => LogBackup::latest()->first()->created_at ?? null,
        ];

        return view('livewire.pengaturan.manajemen-i-t', [
            'whitelist' => $whitelist,
            'logs' => $logs,
            'backups' => $backups,
            'stats' => $stats
        ])->layout('components.layouts.admin', ['title' => 'Manajemen IT & Keamanan']);
    }
}
