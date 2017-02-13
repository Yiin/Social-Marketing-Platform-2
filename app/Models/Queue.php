<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * App\Models\Queue
 *
 * @property int $id
 * @property int $social_media_service_id
 * @property int $client_id
 * @property int $template_id
 * @property array $stats
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static Builder|Queue whereClientId($value)
 * @method static Builder|Queue whereCreatedAt($value)
 * @method static Builder|Queue whereId($value)
 * @method static Builder|Queue whereSocialMediaServiceId($value)
 * @method static Builder|Queue whereStats($value)
 * @method static Builder|Queue whereTemplateId($value)
 * @method static Builder|Queue whereUpdatedAt($value)
 * @method static Queue create($value)
 * @mixin \Eloquent
 */
class Queue extends Model
{
    protected $fillable = [
        'social_media_service_id',
        'client_id',
        'template_id',
        'stats',
        'stats->posts',
        'stats->backlinks'
    ];

    protected $casts = [
        'stats' => 'array',
    ];
}