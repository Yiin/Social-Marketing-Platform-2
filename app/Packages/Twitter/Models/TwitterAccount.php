<?php

namespace App\Packages\Twitter\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @property string twitter_id
 * @property mixed oauth_token
 * @property mixed oauth_secret
 */
class TwitterAccount extends Model
{
    protected $fillable = ['twitter_id', 'name', 'oauth_token', 'oauth_secret'];
}