<?php
namespace App\Services;
use App\Models\Pengaturan;
use App\Models\SensorData;
class AlfdaService
{
    /**
     * ALFDA prototype - Shadow Mode.
     *
     * Menghasilkan rekomendasi, bukan perintah pompa.
     * Ambang dan fungsi keanggotaan masih eksperimental.
     */
    public function evaluate(SensorData $sensor): array
    {
        $started = hrtime(true);

        // 1. Pengaturan admin
        $tdsMin = max(0.0, (float) (
            Pengaturan::where('nama', 'tds_min')->value('nilai') ?? 700
        ));

        $airMin = max(0.0, (float) (
            Pengaturan::where('nama', 'air_min')->value('nilai') ?? 10
        ));

        // 2. Pembacaan sensor
        $tds = $this->number($sensor->tds);
        $level = $this->number($sensor->level_air);
        $ph = $this->number($sensor->ph);
        $waterTemp = $this->number($sensor->suhu);
        $airTemp = $this->number($sensor->suhuudara);
        $humidity = $this->number($sensor->kelembaban);

        // 3. Validasi sensor
        $invalid = $tds === null
            || $level === null
            || $ph === null
            || $waterTemp === null
            || $airTemp === null
            || $humidity === null
            || $tds < 0 || $tds > 5000
            || $level < 0 || $level > 500
            || $ph < 0 || $ph > 14
            || $waterTemp < -10 || $waterTemp > 70
            || $airTemp < -10 || $airTemp > 70
            || $humidity < 0 || $humidity > 100;

        if ($invalid) {
            return $this->result(
                'SENSOR_INVALID',
                'Data sensor tidak lengkap atau tidak valid.',
                0.0,
                0.0,
                0.0,
                $tdsMin,
                $airMin,
                $started
            );
        }

        // 4. Data historis untuk tanaman yang sama
        $previousQuery = SensorData::where('id', '<', $sensor->id);

        if ($sensor->tanaman_id === null) {
            $previousQuery->whereNull('tanaman_id');
        } else {
            $previousQuery->where(
                'tanaman_id',
                $sensor->tanaman_id
            );
        }

        $previous = $previousQuery
            ->orderByDesc('id')
            ->first();

        $previousTds = $previous
            ? $this->number($previous->tds)
            : null;

        $previousLevel = $previous
            ? $this->number($previous->level_air)
            : null;

        $deltaTds = $previousTds !== null
            ? $tds - $previousTds
            : 0.0;

        $deltaLevel = $previousLevel !== null
            ? $level - $previousLevel
            : 0.0;

        // 5. Adaptasi ambang berdasarkan tren sensor
        $tdsShift = $deltaTds < -20
            ? min(abs($deltaTds) * 0.25, $tdsMin * 0.05)
            : 0.0;

        $levelShift = $deltaLevel < -0.5
            ? min(abs($deltaLevel) * 0.25, $airMin * 0.05)
            : 0.0;

        $adaptiveTds = $tdsMin + $tdsShift;
        $adaptiveLevel = $airMin + $levelShift;

        // 6. Fungsi keanggotaan fuzzy
        $nutrientNeed = $this->leftShoulder(
            $tds,
            $adaptiveTds,
            $adaptiveTds + max(50.0, $tdsMin * 0.10)
        );

        $waterNeed = $this->leftShoulder(
            $level,
            $adaptiveLevel,
            $adaptiveLevel + max(1.0, $airMin * 0.10)
        );

        // 7. Status independen
        $nutrientLow = $nutrientNeed >= 0.5;
        $waterLow = $waterNeed >= 0.5;

        // 8. Pemeriksaan keselamatan
        $phWarning = $ph < 5.5 || $ph > 6.5;

        $envWarning = $waterTemp > 32
            || $airTemp > 38
            || $humidity < 20
            || $humidity > 95;

        // 9. Keputusan gabungan
        // Peringatan pH/lingkungan tidak menghapus
        // informasi nutrisi dan air.
        if ($nutrientLow && $waterLow) {
            $decision = 'CHECK_BOTH';
            $message = 'Nutrisi rendah dan level air kurang. '
                . 'Periksa reservoir dan kebutuhan nutrisi.';
            $score = max($nutrientNeed, $waterNeed);

        } elseif ($waterLow) {
            $decision = 'CHECK_WATER';
            $message = 'Level air rendah, periksa reservoir.';
            $score = $waterNeed;

        } elseif ($nutrientLow) {
            $decision = 'CHECK_NUTRIENT';
            $message = 'Nutrisi rendah, periksa kebutuhan nutrisi.';
            $score = $nutrientNeed;

        } elseif ($phWarning) {
            $decision = 'PH_WARNING';
            $message = 'pH larutan di luar rentang pemeriksaan.';
            $score = 1.0;

        } elseif ($envWarning) {
            $decision = 'ENV_WARNING';
            $message = 'Periksa suhu atau kelembapan lingkungan.';
            $score = 1.0;

        } else {
            $decision = 'NORMAL';
            $message = 'Tidak ada tindakan korektif yang direkomendasikan.';
            $score = max(
                0.0,
                1.0 - max($nutrientNeed, $waterNeed)
            );
        }

        // Tambahkan peringatan pendamping bila ada.
        if ($phWarning && $decision !== 'PH_WARNING') {
            $message .= ' Periksa juga pH larutan.';
        }

        if ($envWarning && $decision !== 'ENV_WARNING') {
            $message .= ' Periksa juga kondisi lingkungan.';
        }

        return $this->result(
            $decision,
            $message,
            $score,
            $deltaTds,
            $deltaLevel,
            $adaptiveTds,
            $adaptiveLevel,
            $started
        );
    }

    private function number($value): ?float
    {
        if (
            $value === null
            || $value === ''
            || !is_numeric($value)
        ) {
            return null;
        }

        $number = (float) $value;

        return is_finite($number) ? $number : null;
    }

    private function leftShoulder(
        float $x,
        float $a,
        float $b
    ): float {
        if ($x <= $a) {
            return 1.0;
        }

        if ($x >= $b) {
            return 0.0;
        }

        return ($b - $x) / ($b - $a);
    }

    private function result(
        string $decision,
        string $message,
        float $score,
        float $deltaTds,
        float $deltaLevel,
        float $tdsBoundary,
        float $levelBoundary,
        int $started
    ): array {
        return [
            'decision' => $decision,
            'message' => $message,
            'score' => round($score, 4),
            'delta_tds' => round($deltaTds, 3),
            'delta_level' => round($deltaLevel, 3),
            'adaptive_tds' => round($tdsBoundary, 3),
            'adaptive_level' => round($levelBoundary, 3),
            'inference_time_ms' => round(
                (hrtime(true) - $started) / 1e6,
                4
            ),
            'mode' => 'shadow',
        ];
    }
}
