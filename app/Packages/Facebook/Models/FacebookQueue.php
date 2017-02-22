<?php
/**
 * Created by PhpStorm.
 * User: Stanislovas
 * Date: 2017-02-22
 * Time: 13:35
 */

namespace App\Packages\Facebook\Models;


use App\Models\Client;
use App\Models\Template;
use Illuminate\Database\Eloquent\Model;

class FacebookQueue extends Model
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
        return $this->hasMany(FacebookPost::class);
    }
}