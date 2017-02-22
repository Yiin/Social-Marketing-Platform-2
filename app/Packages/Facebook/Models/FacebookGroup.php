<?php

namespace App\Packages\Facebook\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FacebookAccount
 * @property array groups
 * @property string username
 * @property string password
 * @package App\Packages\Facebook\Models
 */
class FacebookGroup extends Model
{
    /**
     * @var string
     */
    protected $table = 'facebook_groups';

    /**
     * @var array
     */
    protected $fillable = [
        'facebook_account_id',
        'name',
        'groupId',
        'members'
    ];

    public function account()
    {
        return $this->belongsTo(FacebookAccount::class);
    }
}