<?php

namespace App\Modules\Linkedin\Models;

use Illuminate\Database\Eloquent\Model;

class LinkedinGroup extends Model
{
    /**
     * @var string
     */
    protected $table = 'linkedin_groups';

    /**
     * @var array
     */
    protected $fillable = [
        'linkedin_account_id',
        'name',
        'groupId',
        'members'
    ];

    public function account()
    {
        return $this->belongsTo(LinkedinAccount::class);
    }
}