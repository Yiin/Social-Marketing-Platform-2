<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * Class SocialMediaService
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $implementation
 * @property string $view
 * @property string $icon
 * @property array $validation
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static Builder|SocialMediaService whereCreatedAt($value)
 * @method static Builder|SocialMediaService whereIcon($value)
 * @method static Builder|SocialMediaService whereId($value)
 * @method static Builder|SocialMediaService whereImplementation($value)
 * @method static Builder|SocialMediaService whereName($value)
 * @method static Builder|SocialMediaService whereUpdatedAt($value)
 * @method static Builder|SocialMediaService whereValidation($value)
 * @method static Builder|SocialMediaService whereView($value)
 * @mixin \Eloquent
 */
class SocialMediaService extends Model
{
    /**
     * Predefined services
     */
    const SERVICES = [
        // GOOGLE PLUS
        [
            'name' => 'Google+',
            'implementation' => 'App\\Services\\GooglePlusService',
            'view' => 'google-plus',
            'icon' => 'fa fa-google-plus',

            'validation' => [
                'post' => [
                    'template_id' => 'required|exists:templates,id',
                    'client_id' => 'required|exists:clients,id',
                    'delay' => 'required|numeric|min:0'
                ]
            ]
        ]
    ];

    /**
     * @var array
     */
    protected $fillable = ['name', 'implementation', 'validation', 'view', 'icon'];

    /**
     * Array with validation rules
     *
     * @var array
     */
    protected $casts = [
        'validation' => 'array'
    ];

    /**
     * Returns an implementation of SocialMediaServiceInterface
     *
     * @return \App\Interfaces\SocialMediaServiceInterface
     */
    public function impl()
    {
        return resolve($this->implementation);
    }
}
