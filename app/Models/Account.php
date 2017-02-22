<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\Account
 *
 * @property int $id
 * @property int $social_media_service_id
 * @property string $username
 * @property string $password
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\SocialMediaService $socialMediaService
 * @property string app_id
 * @property string app_secret
 * @property string access_token
 * @method static Builder|Account whereCreatedAt($value)
 * @method static Builder|Account whereId($value)
 * @method static Builder|Account wherePassword($value)
 * @method static Builder|Account whereSocialMediaServiceId($value)
 * @method static Builder|Account whereUpdatedAt($value)
 * @method static Builder|Account whereUsername($value)
 * @mixin \Eloquent
 */
class Account extends Model
{
    protected $fillable = [
        'social_media_service_id',
        'username',
        'password',
        'app_id',
        'app_secret',
        'access_token'
    ];

    public function socialMediaService()
    {
        return $this->belongsTo(SocialMediaService::class);
    }

    static public function withGroups($social_media_service_id)
    {
        return Account::where('social_media_service_id', $social_media_service_id)->get()->map(function ($account) {
            if ($account->socialMediaService->getInstance()->login($account->username, $account->password)) {
                $account->groups = $account->socialMediaService->getInstance()->groups();
            } else {
                $account->groups = [];
            }
            return $account;
        });
    }
}
