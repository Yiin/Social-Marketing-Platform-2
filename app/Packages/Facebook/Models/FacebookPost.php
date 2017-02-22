<?php
/**
 * Created by PhpStorm.
 * User: Stanislovas
 * Date: 2017-02-22
 * Time: 13:34
 */

namespace App\Packages\Facebook\Models;


use Illuminate\Database\Eloquent\Model;

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