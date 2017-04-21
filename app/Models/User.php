<?php

namespace App\Models;

use App\Constants\Role;
use App\Modules\Facebook\Models\FacebookQueue;
use App\Modules\GooglePlus\Models\GoogleQueue;
use App\Modules\Linkedin\Models\LinkedinQueue;
use App\Modules\Twitter\Models\TwitterQueue;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $api_token
 * @method static \App\Models\User create($attributes)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereApiToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property mixed clients
 * @property mixed templates
 * @method static \Illuminate\Database\Query\Builder role($roles)
 */
class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'api_token',
        'reseller_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function clients()
    {
        if ($this->hasRole(Role::ADMIN)) {
            return User::role(Role::CLIENT);
        }
        return $this->hasMany(User::class, 'reseller_id')->role(Role::CLIENT);
    }

    public function reseller()
    {
        return $this->belongsTo(User::class, 'reseller_id');
    }

    public function facebookQueues()
    {
        return $this->hasManyThrough(FacebookQueue::class, User::class, 'reseller_id', 'client_id');
    }

    public function googleQueues()
    {
        return $this->hasManyThrough(GoogleQueue::class, User::class, 'reseller_id', 'client_id');
    }

    public function twitterQueues()
    {
        return $this->hasManyThrough(TwitterQueue::class, User::class, 'reseller_id', 'client_id');
    }

    public function linkedinQueues()
    {
        return $this->hasManyThrough(LinkedinQueue::class, User::class, 'reseller_id', 'client_id');
    }

    public function privateTemplates()
    {
        return $this->hasMany(Template::class);
    }

    public function templates()
    {
        return $this->hasManyThrough(Template::class, User::class, 'reseller_id');
    }
}
