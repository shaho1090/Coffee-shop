<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function waiting()
    {
        return self::query()->where('title','waiting')->first();
    }

    public static function preparation()
    {
        return self::query()->where('title','preparation')->first();
    }

    public static function ready()
    {
        return self::query()->where('title','ready')->first();
    }

    public static function delivered()
    {
        return self::query()->where('title','delivered')->first();
    }
}
