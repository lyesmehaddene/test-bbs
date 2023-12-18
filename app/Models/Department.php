<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    /**
     * @var mixed|string
     */
    public mixed $region_name;
    /**
     * @var mixed|string
     */
    public mixed $region_code;
    /**
     * @var mixed|string
     */
    /**
     * @var mixed|string
     */
    protected $fillable = [
        'department_code',
        'department_name',
        'region_code',
        'region_name',
    ];

    /**
     * @return HasMany
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }


//    public function getRegionNameAttribute()
//    {
//        // Add your logic to retrieve the region name
//        // For example, if region_name is a column in the departments table:
//        return $this->department_name;
//    }
}
