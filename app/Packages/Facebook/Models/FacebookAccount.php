<?php

namespace App\Packages\Facebook\Models;

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
 * @package App\Packages\Facebook\Models
 * @property int $id
 * @property string $fbid
 * @property string $name
 * @property string $access_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Packages\Facebook\Models\FacebookGroup[] $groups
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Facebook\Models\FacebookAccount whereAccessToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Facebook\Models\FacebookAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Facebook\Models\FacebookAccount whereFbid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Facebook\Models\FacebookAccount whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Facebook\Models\FacebookAccount whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\Facebook\Models\FacebookAccount whereUpdatedAt($value)
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