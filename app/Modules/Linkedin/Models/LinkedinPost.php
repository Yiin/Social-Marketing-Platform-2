<?php

namespace App\Modules\Linkedin\Models;

use Illuminate\Database\Eloquent\Model;

class LinkedinPost extends Model
{
    protected $fillable = [
        'linkedin_queue_id',
        'url',
        'message',
        'group_name',
    ];

    public function queue()
    {
        return $this->belongsTo(LinkedinQueue::class);
    }
}