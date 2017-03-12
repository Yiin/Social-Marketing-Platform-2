<?php

namespace App\Modules\Facebook\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FacebookAccount
 *
 * @property array groups
 * @property string username
 * @property string password
 * @package App\Modules\Facebook\Models
 * @property int $id
 * @property int $facebook_account_id
 * @property string $name
 * @property string $groupId
 * @property int $members
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Modules\Facebook\Models\FacebookAccount $account
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookGroup whereFacebookAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookGroup whereGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookGroup whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookGroup whereMembers($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookGroup whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookGroup whereUpdatedAt($value)
 * @mixin \Eloquent
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