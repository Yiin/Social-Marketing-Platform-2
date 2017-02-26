<?php

namespace App\Packages\Twitter\Models;


use App\Models\Client;
use App\Models\Template;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Packages\Twitter\Models\TwitterQueue
 *
 * @property integer client_id
 * @property integer template_id
 * @property integer tweet_count
 * @property integer jobs
 * @property Client client
 * @property Template template
 * @property array tweets
 * @property mixed id
 * @property int $id
 * @property int $client_id
 * @property int $template_id
 * @property int $tweet_count
 * @property int $jobs
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Client $client
 * @property-read \App\Models\Template $template
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Packages\Twitter\Models\Tweet[] $tweets
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Twitter\Models\TwitterQueue whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Twitter\Models\TwitterQueue whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Twitter\Models\TwitterQueue whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Twitter\Models\TwitterQueue whereJobs($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Twitter\Models\TwitterQueue whereTemplateId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Twitter\Models\TwitterQueue whereTweetCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Twitter\Models\TwitterQueue whereUpdatedAt($value)
 * @mixin \Eloquent
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