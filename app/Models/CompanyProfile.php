<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    protected $table = 'company_profiles';
    protected $primaryKey = 'profile_id';
    
    protected $fillable = [
        'about', 'vision', 'mission', 'history', 'address', 'phone', 'email', 'updated_by'
    ];
}
