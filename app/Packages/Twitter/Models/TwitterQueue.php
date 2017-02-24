<?php

namespace App\Packages\Twitter\Models;


use App\Models\Client;
use App\Models\Template;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer client_id
 * @property integer template_id
 * @property integer tweet_count
 * @property integer jobs
 * @property Client client
 * @property Template template
 * @property array tweets
 * @property mixed id
 */
class TwitterQueue extends Model
{
    protected $fillable = [
        'client_id', 'template_id', 'tweet_count', 'jobs'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function tweets()
    {
        return $this->hasMany(Tweet::class);
    }
}