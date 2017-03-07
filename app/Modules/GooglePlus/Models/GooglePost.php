<?php

namespace App\Modules\GooglePlus\Models;

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
 * @property int $google_queue_id
 * @property-read \App\Modules\GooglePlus\Models\GoogleQueue $queue
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\GooglePlus\Models\GooglePost whereCommunityName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\GooglePlus\Models\GooglePost whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\GooglePlus\Models\GooglePost whereGoogleQueueId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\GooglePlus\Models\GooglePost whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\GooglePlus\Models\GooglePost whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\GooglePlus\Models\GooglePost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\GooglePlus\Models\GooglePost whereUrl($value)
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
