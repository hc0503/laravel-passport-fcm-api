<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'cover_photo',
        'profile_photo',
        'stage_name',
        'about_you',
        'categories',
        'tags',

        'name',
        'interested_in',
        'organization_type',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
    ];
}
