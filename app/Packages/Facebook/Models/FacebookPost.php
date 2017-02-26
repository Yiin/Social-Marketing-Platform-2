<?php
/**
 * Created by PhpStorm.
 * User: Stanislovas
 * Date: 2017-02-22
 * Time: 13:34
 */

namespace App\Packages\Facebook\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Packages\Facebook\Models\FacebookPost
 *
 * @property int $id
 * @property int $facebook_queue_id
 * @property string $url
 * @property string $message
 * @property string $group_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Packages\Facebook\Models\FacebookQueue $queue
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Facebook\Models\FacebookPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Facebook\Models\FacebookPost whereFacebookQueueId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Facebook\Models\FacebookPost whereGroupName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Facebook\Models\FacebookPost whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Facebook\Models\FacebookPost whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Facebook\Models\FacebookPost whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Facebook\Models\FacebookPost whereUrl($value)
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