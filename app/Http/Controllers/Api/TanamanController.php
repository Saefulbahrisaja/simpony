<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use App\Models\SensorData;
use App\Models\Tanaman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TanamanController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Tanaman::query()
                ->orderBy('nama_tanaman')
                ->get(['id', 'nama_tanaman', 'nama_ilmiah', 'hst', 'hss', 'status']),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->rules());

        $tanaman = DB::transaction(function () use ($validated) {
            $tanaman = Tanaman::create($validated);

            if ($tanaman->status === 'aktif') {
                $this->activate($tanaman);
            }

            return $tanaman->fresh();
        });

        return response()->json([
            'success' => true,
            'message' => 'Tanaman berhasil ditambahkan.',
            'data' => $tanaman,
        ], 201);
    }

    public function show(Tanaman $tanaman): JsonResponse
    {
        return response()->json(['data' => $tanaman]);
    }

    public function update(Request $request, Tanaman $tanaman): JsonResponse
    {
        $validated = $request->validate($this->rules(true));

        $tanaman = DB::transaction(function () use ($validated, $tanaman) {
            $tanaman->update($validated);

            if ($tanaman->status === 'aktif') {
                $this->activate($tanaman);
            } else {
                Pengaturan::where('nama', 'tanaman_aktif')
                    ->where('nilai', $tanaman->id)
                    ->delete();
            }

            return $tanaman->fresh();
        });

        return response()->json([
            'success' => true,
            'message' => 'Data tanaman berhasil diperbarui.',
            'data' => $tanaman,
        ]);
    }

    public function destroy(Tanaman $tanaman): JsonResponse
    {
        if (SensorData::where('tanaman_id', $tanaman->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Tanaman tidak dapat dihapus karena sudah memiliki riwayat sensor.',
            ], 409);
        }

        DB::transaction(function () use ($tanaman) {
            Pengaturan::where('nama', 'tanaman_aktif')
                ->where('nilai', $tanaman->id)
                ->delete();

            $tanaman->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Tanaman berhasil dihapus.',
        ]);
    }

    private function rules(bool $partial = false): array
    {
        return [
            'nama_tanaman' => array_merge($partial ? ['sometimes', 'required'] : ['required'], ['string', 'max:255']),
            'nama_ilmiah' => array_merge($partial ? ['sometimes'] : [], ['nullable', 'string', 'max:255']),
            'hst' => array_merge($partial ? ['sometimes'] : [], ['nullable', 'date']),
            'hss' => array_merge($partial ? ['sometimes'] : [], ['nullable', 'date']),
            'status' => ['sometimes', 'in:aktif,nonaktif'],
        ];
    }

    private function activate(Tanaman $tanaman): void
    {
        Tanaman::where('id', '!=', $tanaman->id)->update(['status' => 'nonaktif']);
        Pengaturan::updateOrCreate(
            ['nama' => 'tanaman_aktif'],
            ['nilai' => $tanaman->id]
        );
    }
}
