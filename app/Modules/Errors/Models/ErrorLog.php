<?php

namespace App\Modules\Errors\Models;

use Illuminate\Database\Eloquent\Model;
use Log;

class ErrorLog extends Model
{
    protected $fillable = ['message'];

    public static function report($message)
    {
        Log::error($message);

        self::create(compact('message'));
    }
}
