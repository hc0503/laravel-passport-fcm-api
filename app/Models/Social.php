<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\HasGuidTrait;

class Social extends Model
{
    use HasFactory, HasGuidTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'guid',
        'profile_id',
        'provider',
        'social_id',
        'token',
        'refresh_token',
        'expires_in',
        'nickname',
        'name',
        'email',
        'avatar',
        'created_at',
        'updated_at',
    ];
}
