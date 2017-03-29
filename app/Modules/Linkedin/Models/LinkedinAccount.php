<?php

namespace App\Modules\Linkedin\Models;

use Illuminate\Database\Eloquent\Model;

class LinkedinAccount extends Model
{
    protected $table = 'linkedin_accounts';

    protected $fillable = ['email', 'password'];

    public function groups()
    {
        return $this->hasMany(LinkedinGroup::class);
    }
}