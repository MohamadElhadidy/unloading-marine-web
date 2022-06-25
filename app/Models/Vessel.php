<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vessel extends Model
{
    use HasFactory, Notifiable;
    protected $table = "vessels_log";

    // protected static function booted()
    // {
    //     static::created(function ($vessel) {
    //         $notifications = new Notifications;
    //         $notifications->send('تم تعديل الباخرة  بنجاح');
    //     });
    // }

    protected $fillable = [
        'vessel_id',
        'name',
        'img_id',
        'qnt',
        'type',
        'client',
        'shipping_agency',
        'notes',
        'quay',
        'done',
        'archive',
        'token',
        'start_date',
        'exit_date',
    ];
    public function images()
    {
        return $this->belongsTo(VesselsImage::class, 'img_id');
    }

}