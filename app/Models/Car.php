<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $table = "cars";
    public $timestamps = false;
    protected $fillable = [
        'vessel_id',
        'sn',
        'car_no',
        'car_type ',
        'car_owner',
        'done',
        'start_date',
        'exit_date',
        'mehwer',
        'empty',
        'limits',
        'all_hours',
        'moves',
        'cost_type',
        'cost',
        'all_cost'
    ];
}