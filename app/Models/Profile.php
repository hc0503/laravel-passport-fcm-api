<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\HasGuidTrait;

class Profile extends Model
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

    /**
     * Get the socials associated with the profile.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socials()
    {
        return $this->hasMany(Social::class);
    }
}
