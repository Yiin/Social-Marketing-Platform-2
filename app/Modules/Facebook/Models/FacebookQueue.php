<?php
/**
 * Created by PhpStorm.
 * User: Stanislovas
 * Date: 2017-02-22
 * Time: 13:35
 */

namespace App\Modules\Facebook\Models;


use App\Models\Client;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Modules\Facebook\Models\FacebookQueue
 *
 * @property int $id
 * @property int $client_id
 * @property int $template_id
 * @property int $post_count
 * @property int $backlinks
 * @property int $jobs
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Client $client
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Modules\Facebook\Models\FacebookPost[] $posts
 * @property-read \App\Models\Template $template
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookQueue whereBacklinks($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookQueue whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookQueue whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookQueue whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookQueue whereJobs($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookQueue wherePostCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookQueue whereTemplateId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Modules\Facebook\Models\FacebookQueue whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FacebookQueue extends Model
{
    protected $fillable = [
        'client_id', 'template_id', 'post_count', 'backlinks', 'jobs'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function posts()
    {
        return $this->hasMany(FacebookPost::class);
    }
}