<?php

namespace App\Laravel\Models;

use App\Laravel\Traits\DateFormatterTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{

    use SoftDeletes;

    protected $table = "province";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','psgcCode','provDesc','regCode','provCode'
    ];

    public function municipalities(){
      return $this->hasMany(City::class, 'provCode', 'provCode')
                ->select('id','citymunDesc','provCode');
    }
}
