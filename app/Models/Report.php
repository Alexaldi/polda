<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'incident_datetime',
        'province_id',
        'city_id',
        'district_id',
        'address_detail',
        'category_id',
        'status',
        'code',
        'finish_time',
    ];

    public function suspects()
    {
        return $this->hasMany(Suspect::class);
    }

    public function reportJourneys()
    {
        return $this->hasMany(ReportJourney::class);
    }

    public function reportCategory()
    {
        return $this->belongsTo(ReportCategory::class, 'category_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
