<?php 

namespace App\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Laravel\Traits\DateFormatter;
use Str;

class OrderDetails extends Model{
    
    use SoftDeletes,DateFormatter;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "order_details";

    /**
     * The database connection used by the model.
     *
     * @var string
     */
    protected $connection = "master_db";

    /**
     * Enable soft delete in table
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_id','request_time','transaction_number','designation_number','no_of_pages','no_of_copies','company_name','order_title','title','first_name','middle_name','last_name','email','tel_no',
                            'unit_no','street_name','brgy','municipality','province','region','zip_code','sector','purpose','price'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that created within the model.
     *
     * @var array
     */
    protected $appends = [];

    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

     public function getAddressAttribute(){
        return Str::title("{$this->unit_no} {$this->street_name} , {$this->brgy} , {$this->municipality} , {$this->province} , {$this->region} {$this->zip_code}");
    }
}