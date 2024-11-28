<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlarmHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_time',
        'message',
        'state_change_type',
        'alarm_class',
        'alarm_count',
        'alarm_group',
        'name',
        'alarm_state',
        'condition',
        'current_value',
        'inhibit_state',
        'limit_value_exceeded',
        'priority',
        'severity',
        'tag1_value',
        'tag2_value',
        'tag3_value',
        'tag4_value',
        'event_category',
        'quality',
        'expression',
    ];

    public static function insert(array $data)
    {
    }
}


