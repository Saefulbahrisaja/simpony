<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\SensorData;
use App\Models\DiseaseDetection;
use App\Models\AlfdaDecision;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'hari');
        $query = SensorData::query();

        // Filter data sensor
        if ($filter === 'hari') {
            $query->whereDate('created_at', Carbon::today());

        } elseif ($filter === 'minggu') {
            $query->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);

        } elseif ($filter === 'bulan') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        }

        // Data historis sensor
        $dataSensor = $query->orderBy('created_at')->get();

        // Ambil 50 data sensor terbaru
        $data = SensorData::with('tanaman')
            ->latest()
            ->take(50)
            ->get();

        $latestSensor = $data->first();

        // ==========================================
        // ALFDA INTELLIGENT DECISION
        // ==========================================

        $latestAlfda = $latestSensor
    ? AlfdaDecision::where('sensor_data_id', $latestSensor->id)
        ->orderByDesc('id')
        ->first()
    : null;

        // ==========================================
        // DATA GRAFIK SENSOR
        // ==========================================

        $waktu = $dataSensor->pluck('created_at')
            ->map(fn ($w) => $w->format('H:i'))
            ->toArray();

        $suhu = $dataSensor->pluck('suhu')->toArray();
        $tds = $dataSensor->pluck('tds')->toArray();
        $ph = $dataSensor->pluck('ph')->toArray();
        $suhuudara = $dataSensor->pluck('suhuudara')->toArray();
        $kelembaban = $dataSensor->pluck('kelembaban')->toArray();

        // ==========================================
        // ANALISIS PENYAKIT TANAMAN
        // ==========================================

        $latestDisease = null;
        $diseaseCount = 0;
        $healthyCount = 0;
        $diseaseToday = 0;

        if (Schema::hasTable('disease_detections')) {

            $latestDisease = DiseaseDetection::with('tanaman')
                ->latest('detected_at')
                ->first();

            $diseaseCount = DiseaseDetection::where(
                'disease', '!=', 'healthy'
            )->count();

            $healthyCount = DiseaseDetection::where(
                'disease', 'healthy'
            )->count();

            $diseaseToday = DiseaseDetection::whereDate(
                'detected_at', Carbon::today()
            )
            ->where('disease', '!=', 'healthy')
            ->count();
        }

        // ==========================================
        // KIRIM DATA KE DASHBOARD
        // ==========================================

        return view('dashboard.index', compact(
    'waktu', 'suhu', 'tds', 'ph',
    'suhuudara', 'kelembaban',
    'data', 'latestSensor', 'latestAlfda',
    'latestDisease', 'diseaseCount',
    'healthyCount', 'diseaseToday', 'filter'
));
    }
}
