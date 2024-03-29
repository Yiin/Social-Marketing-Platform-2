<?php

namespace App\Modules\GooglePlus\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoogleAccount
 *
 * @property array groups
 * @property string username
 * @property string password
 * @package App\Modules\GooglePlus\Models
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int|null user_id
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\GooglePlus\Models\GoogleAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\GooglePlus\Models\GoogleAccount whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\GooglePlus\Models\GoogleAccount wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\GooglePlus\Models\GoogleAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\GooglePlus\Models\GoogleAccount whereUsername($value)
 * @mixin \Eloquent
 */
class GoogleAccount extends Model
{
    /**
     * @var string
     */
    protected $table = 'google_accounts';

    /**
     * @var array
     */
    protected $fillable = [
        'username',
        'password'
    ];
}