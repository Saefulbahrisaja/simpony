<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorData;
use App\Models\Pengaturan;
use Illuminate\Http\JsonResponse;
use App\Models\Tanaman;
use Illuminate\Support\Facades\Validator;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        $payload = $request->all();

        // NaN/Infinity bukan nilai JSON yang valid. Beberapa firmware tetap
        // mengirim token tersebut, jadi ubah hanya nilai numerik non-finite
        // menjadi null sebelum divalidasi.
        if (empty($payload) && trim($request->getContent()) !== '') {
            $normalizedJson = preg_replace(
                '/(:\s*)(?:NaN|[-+]?Infinity)(?=\s*[,}\]])/i',
                '$1null',
                $request->getContent()
            );
            $decodedPayload = json_decode($normalizedJson, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedPayload)) {
                $payload = $decodedPayload;
            }
        }

        $validator = Validator::make($payload, [
            'data' => ['required', 'array'],
            'data.suhu' => ['required', 'numeric'],
            'data.kelembaban' => ['required', 'numeric'],
            'data.suhuudara' => ['required', 'numeric'],
            'data.ph' => ['nullable', 'numeric'],
            'data.tds' => ['nullable', 'numeric'],
            'data.pompa_air' => ['nullable', 'boolean'],
            'data.pompa_nutrisi' => ['nullable', 'boolean'],
            'data.level_air' => ['nullable', 'numeric'],
            'data.air_min' => ['nullable', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data sensor tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated()['data'];

        // Hindari foreign key error jika pengaturan masih menunjuk tanaman yang sudah dihapus.
        $tanamanAktifId = Pengaturan::where('nama', 'tanaman_aktif')->value('nilai')
            ?? Tanaman::where('status', 'aktif')->value('id');
        $tanamanAktifId = $tanamanAktifId ? Tanaman::whereKey((int) $tanamanAktifId)->value('id') : null;

        // Simpan data sensor ke database
        $sensor = SensorData::create([
            'suhu'          => $data['suhu'],
            'kelembaban'    => $data['kelembaban'],
            'suhuudara'     => $data['suhuudara'],
            'ph'            => $data['ph'] ?? null,
            'tds'           => $data['tds'] ?? null,
            'pompa_air'     => $data['pompa_air'] ?? null,
            'pompa_nutrisi' => $data['pompa_nutrisi'] ?? null,
            'level_air'     => $data['level_air'] ?? null,
            'air_min'       => $data['air_min'] ?? null,
            'tanaman_id'    => $tanamanAktifId, // <<— disimpan otomatis
        ]);

        return response()->json([
            'success' => true,
            'data' => $sensor
        ], 201);
    }

    public function getBatas()
    {
        $tdsMin   = Pengaturan::where('nama', 'tds_min')->value('nilai') ?? 700;
        $airMin   = Pengaturan::where('nama', 'air_min')->value('nilai') ?? 10;
        $interval = Pengaturan::where('nama', 'interval')->value('nilai') ?? 10; 
        $tanamanAktif = Pengaturan::where('nama', 'tanaman_aktif')->value('nilai')
            ?? Tanaman::where('status', 'aktif')->value('id');

        return response()->json([
            'tds_min'  => (float) $tdsMin,
            'air_min'  => (float) $airMin,
            'interval' => (int) $interval,
            'tanaman_aktif' => $tanamanAktif ? (int) $tanamanAktif : null,
        ]);
    }

    public function updateBatas(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tds_min' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'air_min' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'interval' => ['sometimes', 'nullable', 'numeric', 'min:1'],
            'tanaman_aktif' => ['sometimes', 'nullable', 'integer', 'exists:tanaman,id'],
        ]);

        // Field kosong dari form akan menjadi null. Pertahankan nilai tersimpan;
        // gunakan default hanya jika pengaturan tersebut belum pernah dibuat.
        $resolveValue = static function (string $nama, $default) use ($validated) {
            $value = $validated[$nama] ?? null;

            if ($value === null || $value === '') {
                $value = Pengaturan::where('nama', $nama)->value('nilai') ?? $default;
            }

            return $value;
        };

        $tdsMin = $resolveValue('tds_min', 700);
        $airMin = $resolveValue('air_min', 10);
        $interval = $resolveValue('interval', 10);
        $tanamanAktif = $validated['tanaman_aktif'] ?? null;

        Pengaturan::updateOrCreate(['nama' => 'tds_min'], ['nilai' => $tdsMin]);
        Pengaturan::updateOrCreate(['nama' => 'air_min'], ['nilai' => $airMin]);
        Pengaturan::updateOrCreate(['nama' => 'interval'], ['nilai' => $interval]);

        if ($tanamanAktif !== null && $tanamanAktif !== '') {
            Pengaturan::updateOrCreate(['nama' => 'tanaman_aktif'], ['nilai' => $tanamanAktif]);
            Tanaman::where('id', '!=', $tanamanAktif)->update(['status' => 'nonaktif']);
            Tanaman::whereKey($tanamanAktif)->update(['status' => 'aktif']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan berhasil disimpan!',
            'data' => [
                'tds_min' => $tdsMin,
                'air_min' => $airMin,
                'interval' => $interval,
                'tanaman_aktif' => $tanamanAktif,
            ]
        ]);
    }

/**
     * Ambil detail tanaman aktif dari tabel tanaman
     */
    public function getTanamanAktif(): JsonResponse
    {
        $tanamanAktifId = Pengaturan::where('nama', 'tanaman_aktif')->value('nilai')
            ?? Tanaman::where('status', 'aktif')->value('id');

        if (!$tanamanAktifId) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada tanaman aktif yang diset',
                'data' => null
            ], 404);
        }

        $tanaman = Tanaman::find($tanamanAktifId);

        if (!$tanaman) {
            return response()->json([
                'success' => false,
                'message' => 'Tanaman aktif tidak ditemukan di database',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $tanaman
        ]);
    }

}
