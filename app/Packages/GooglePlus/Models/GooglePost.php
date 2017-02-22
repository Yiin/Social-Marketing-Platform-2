<?php

namespace App\Packages\GooglePlus\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Post
 *
 * @property int $id
 * @property int $queue_id
 * @property string $url
 * @property string $message
 * @property string $community_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @mixin \Eloquent
 */
class GooglePost extends Model
{
    protected $fillable = [
        'google_queue_id',
        'url',
        'message',
        'community_name',
    ];

    public function queue()
    {
        return $this->belongsTo(GoogleQueue::class);
    }
}
