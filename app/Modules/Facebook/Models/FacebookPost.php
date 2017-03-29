<?php

namespace App\Modules\Facebook\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Modules\Facebook\Models\FacebookPost
 *
 * @property int $id
 * @property int $facebook_queue_id
 * @property string $url
 * @property string $message
 * @property string $group_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Modules\Facebook\Models\FacebookQueue $queue
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookPost whereFacebookQueueId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookPost whereGroupName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookPost whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookPost whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookPost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookPost whereUrl($value)
 * @mixin \Eloquent
 */
class FacebookPost extends Model
{
    protected $fillable = [
        'facebook_queue_id',
        'url',
        'message',
        'group_name',
    ];

    public function queue()
    {
        return $this->belongsTo(FacebookQueue::class);
    }
}