<?php

namespace App\Modules\Twitter\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Modules\Twitter\Models\TwitterAccount
 *
 * @property string twitter_id
 * @property mixed oauth_token
 * @property mixed oauth_secret
 * @property int $id
 * @property int user_id
 * @property string $name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Twitter\Models\TwitterAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Twitter\Models\TwitterAccount whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Twitter\Models\TwitterAccount whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Twitter\Models\TwitterAccount whereOauthSecret($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Twitter\Models\TwitterAccount whereOauthToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Twitter\Models\TwitterAccount whereTwitterId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Twitter\Models\TwitterAccount whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TwitterAccount extends Model
{
    protected $fillable = ['twitter_id', 'name', 'oauth_token', 'oauth_secret'];
}