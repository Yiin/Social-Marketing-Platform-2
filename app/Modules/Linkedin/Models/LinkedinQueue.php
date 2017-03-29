<?php

namespace App\Modules\Linkedin\Models;

use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class LinkedinQueue extends Model
{
    protected $fillable = [
        'client_id', 'template_id', 'post_count', 'backlinks', 'jobs'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function posts()
    {
        return $this->hasMany(LinkedinPost::class);
    }
}