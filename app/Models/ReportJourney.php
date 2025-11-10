<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportJourney extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'institution_id',
        'division_id',
        'type',
        'description',
    ];
}
