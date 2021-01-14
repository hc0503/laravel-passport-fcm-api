<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\HasGuidTrait;

class Notification extends Model
{
    use HasFactory, HasGuidTrait;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'type',
        'notification_type',
        'title',
        'body',
        'is_archive'
    ];

    protected $casts = [
        'is_archive' => 'boolean',
    ];
}