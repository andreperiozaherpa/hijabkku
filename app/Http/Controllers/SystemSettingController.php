<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    /**
     * Display the system settings page.
     */
    public function index()
    {
        $xenditSimulationMode = SystemSetting::getByKey('xendit_simulation_mode', 'false');
        $fiturSesiKasir = SystemSetting::getByKey('fitur_sesi_kasir', 'true');
        $fiturFotoProduk = SystemSetting::getByKey('fitur_foto_produk', 'true');

        return view('manajemen_user.pengaturan', [
            'xenditSimulationMode' => $xenditSimulationMode,
            'fiturSesiKasir' => $fiturSesiKasir,
            'fiturFotoProduk' => $fiturFotoProduk,
        ]);
    }

    /**
     * Update a specific system setting.
     */
    public function update(Request $request)
    {
        $key = $request->key;
        $value = $request->value;

        if (! in_array($key, ['xendit_simulation_mode', 'fitur_sesi_kasir', 'fitur_foto_produk'])) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Gagal',
                'text' => 'Kunci pengaturan tidak valid',
            ]);
        }

        SystemSetting::setByKey($key, $value);

        return response()->json([
            'icon' => 'success',
            'title' => 'Sukses',
            'text' => 'Pengaturan sistem berhasil diperbarui.',
        ]);
    }
}
