<?php

namespace App\Modules\Facebook\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FacebookAccount
 *
 * @property array groups
 * @property string username
 * @property string password
 * @property mixed access_token
 * @property string name
 * @property string fbid
 * @package App\Modules\Facebook\Models
 * @property int $id
 * @property string user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookAccount whereAccessToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookAccount whereFbid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookAccount whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookAccount whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookAccount whereUpdatedAt($value)
 * @mixin \Eloquent
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