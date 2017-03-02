<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Template
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $url
 * @property string $name
 * @property string $image_url
 * @property string $description
 * @property string $message
 * @property string $caption
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int|null user_id
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Template whereCaption($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Template whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Template whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Template whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Template whereImageUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Template whereMessage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Template whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Template whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Template whereUrl($value)
 */
class Template extends Model
{
    protected $fillable = [
        'url',
        'name',
        'image_url',
        'description',
        'message',
        'caption'
    ];
}
