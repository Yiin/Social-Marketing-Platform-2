<?php
/**
 * Created by PhpStorm.
 * User: Stanislovas
 * Date: 2017-02-22
 * Time: 1:55
 */

namespace App\Packages\GooglePlus\Models;

use App\Models\Client;
use App\Models\Template;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Packages\GooglePlus\Models\GoogleQueue
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Packages\GooglePlus\Models\GooglePost[] $posts
 * @property-read \App\Models\Template $template
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\GooglePlus\Models\GoogleQueue whereBacklinks($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\GooglePlus\Models\GoogleQueue whereClientId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\GooglePlus\Models\GoogleQueue whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\GooglePlus\Models\GoogleQueue whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\GooglePlus\Models\GoogleQueue whereJobs($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\GooglePlus\Models\GoogleQueue wherePostCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\GooglePlus\Models\GoogleQueue whereTemplateId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Packages\GooglePlus\Models\GoogleQueue whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class GoogleQueue extends Model
{
    protected $fillable = [
        'client_id', 'template_id', 'post_count', 'backlinks', 'jobs'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function posts()
    {
        return $this->hasMany(GooglePost::class);
    }
}