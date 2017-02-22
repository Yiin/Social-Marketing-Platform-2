<?php

namespace App\Packages\Facebook\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FacebookAccount
 * @property array groups
 * @property string username
 * @property string password
 * @property mixed access_token
 * @property string name
 * @property string fbid
 * @package App\Packages\Facebook\Models
 */
class FacebookAccount extends Model
{
    /**
     * @var string
     */
    protected $table = 'facebook_accounts';

    /**
     * @var array
     */
    protected $fillable = [
        'fbid',
        'name',
        'access_token'
    ];

    public function groups()
    {
        return $this->hasMany(FacebookGroup::class);
    }
}