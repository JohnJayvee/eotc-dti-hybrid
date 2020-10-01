<?php

namespace App\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use App\Laravel\Traits\DateFormatter;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth,Helper;

class TransactionRequirements extends Model
{
    use SoftDeletes,DateFormatter;

    protected $table = "transaction_requirements";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id', 'type'
    ];
    
    public $timestamps = true;
}
