<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AlfdaDecision extends Model
{
    protected $table = 'alfda_decisions';
    protected $fillable = [
        'sensor_data_id', 'tanaman_id', 'decision', 'message', 'score',
        'delta_tds', 'delta_level', 'adaptive_tds', 'adaptive_level',
        'inference_time_ms', 'mode'
    ];
}
