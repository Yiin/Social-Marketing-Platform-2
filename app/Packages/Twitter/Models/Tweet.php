<?php
/**
 * Created by PhpStorm.
 * User: Stanislovas
 * Date: 2017-02-24
 * Time: 14:57
 */

namespace App\Packages\Twitter\Models;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $fillable = ['twitter_queue_id', 'url', 'message'];

    public function queue()
    {
        return $this->belongsTo(TwitterQueue::class);
    }
}