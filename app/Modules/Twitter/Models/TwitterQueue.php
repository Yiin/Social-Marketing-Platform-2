<?php

namespace App\Modules\Twitter\Models;


use App\Models\Client;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Modules\Twitter\Models\TwitterQueue
 *
 * @property integer client_id
 * @property integer template_id
 * @property integer tweet_count
 * @property integer jobs
 * @property Client client
 * @property Template template
 * @property array tweets
 * @property mixed id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Twitter\Models\TwitterQueue whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Twitter\Models\TwitterQueue whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Twitter\Models\TwitterQueue whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Twitter\Models\TwitterQueue whereJobs($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Twitter\Models\TwitterQueue whereTemplateId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Twitter\Models\TwitterQueue whereTweetCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Twitter\Models\TwitterQueue whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TwitterQueue extends Model
{
    protected $fillable = [
        'client_id', 'template_id', 'tweet_count', 'jobs'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
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