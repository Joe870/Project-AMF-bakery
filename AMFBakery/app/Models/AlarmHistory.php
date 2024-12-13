<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlarmHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'EventTime', 'Message', 'StateChangeType', 'AlarmClass', 'AlarmCount',
        'AlarmGroup', 'Name', 'AlarmState', 'Condition', 'CurrentValue',
        'InhibitState', 'LimitValueExceeded', 'Priority', 'Severity',
        'Tag1Value', 'Tag2Value', 'Tag3Value', 'Tag4Value', 'EventCategory',
        'Quality', 'Expression',
    ];
}