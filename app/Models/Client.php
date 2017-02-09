<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Client
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Client whereUpdatedAt($value)
 */
class Client extends Model
{
    protected $fillable = ['name', 'email'];
}
