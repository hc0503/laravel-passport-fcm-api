<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\HasGuidTrait;

class FcmDeviceToken extends Model
{
    use HasFactory, HasGuidTrait;

    protected $table = 'fcm_device_tokens';

    protected $fillable = [
        'user_id',
        'token',
        'type'
    ];
}