<?php 

namespace App\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Laravel\Traits\DateFormatter;
use App\Console\Commands\SendMail;
use Str,Carbon,Helper;

class OrderTransaction extends Model{
    
    use SoftDeletes,DateFormatter;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "order_transaction";

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
    protected $fillable = ['order_transaction_number','payor','email','contact_number','department','transaction_code','payment_category','total_amount'];


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

    public function order(){
        return $this->BelongsTo("App\Laravel\Models\OrderDetails",'order_transaction_number','transaction_number');
    }

    public function scopeImport(){
        $data= $this->where('created_at', '>=', Carbon::now()->subMinutes(10)->toDateTimeString())->get();
        if ($data) {
            foreach ($data as $key => $value) {
                $sum_amount = OrderDetails::where('transaction_number' , $value->order_transaction_number)->sum('price');
                OrderTransaction::where('order_transaction_number',$value->order_transaction_number)->update(['total_amount' => $sum_amount]);
               
            }
            //$details = [
                //'subject' => 'Order Payment Details'
            // ];
            //$job = (new SendMail($details))->delay(now()->addSeconds(10)); 
            //dispatch($job);   
        }
        
    }

}