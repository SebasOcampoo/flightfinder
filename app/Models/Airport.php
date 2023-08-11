<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'lat',
        'lng'
    ];

    public function departures()
    {
        return $this->hasMany(Flight::class, 'code_departure', 'code');
    }

    public function arrivals()
    {
        return $this->hasMany(Flight::class, 'code_arrival', 'code');
    }

}
