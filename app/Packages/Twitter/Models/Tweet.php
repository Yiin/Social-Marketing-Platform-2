<?php
/**
 * Created by PhpStorm.
 * User: Stanislovas
 * Date: 2017-02-24
 * Time: 14:57
 */

namespace App\Packages\Twitter\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Packages\Twitter\Models\Tweet
 *
 * @property int $id
 * @property int $twitter_queue_id
 * @property string $url
 * @property string $message
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Packages\Twitter\Models\TwitterQueue $queue
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Twitter\Models\Tweet whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Twitter\Models\Tweet whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Twitter\Models\Tweet whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Twitter\Models\Tweet whereTwitterQueueId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Twitter\Models\Tweet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Twitter\Models\Tweet whereUrl($value)
 * @mixin \Eloquent
 */
class Tweet extends Model
{
    protected $fillable = ['twitter_queue_id', 'url', 'message'];

    public function queue()
    {
        return $this->belongsTo(TwitterQueue::class);
    }
}