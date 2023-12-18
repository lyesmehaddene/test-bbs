<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'insee_city_code',
        'postal_city_name',
        'postal_code',
        'acheminement_name',
        'line_5',
        'Latitude',
        'Longitude',
        'city_code',
        'article',
        'city_name',
        'city_full_name',
        'department_id',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }


}
