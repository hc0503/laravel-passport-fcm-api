<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Helpers\HasGuidTrait;


class GigItem extends Model
{
    use HasFactory, HasGuidTrait;
    
    protected $table = 'gig_items';

    protected $fillable = [

    ];
}
