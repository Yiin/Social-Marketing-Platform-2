<?php

namespace App\Packages\GooglePlus\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoogleAccount
 * @property array groups
 * @property string username
 * @property string password
 * @package App\Packages\GooglePlus\Models
 */
class GoogleAccount extends Model
{
    /**
     * @var string
     */
    protected $table = 'google_accounts';
    
    /**
     * @var array
     */
    protected $fillable = [
        'username',
        'password'
    ];
}