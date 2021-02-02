<?php 

namespace App\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Laravel\Traits\DateFormatter;
use Str,Carbon,Helper,DB;

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

    public function getFullNameAttribute(){
        return Str::title("{$this->first_name} {$this->last_name} ");
    }
    public function scopeCreateTransaction(){
        $data= $this->where('created_at', '>=', Carbon::now()->subMinutes(10)->toDateTimeString())->get();

        if ($data) {
            foreach ($data as $key => $value) {
                //$order_transaction = OrderTransaction::where('order_transaction_number' , $value->transaction_number)->first();
                    
                    $sum_amount = OrderDetails::where('transaction_number' , $value->transaction_number)->sum('price');
                    OrderTransaction::firstOrCreate(
                        ['order_transaction_number' => $value->transaction_number],
                        [
                            'fname' => $value->first_name , 
                            'mname' => $value->middle_name,
                            'lname' => $value->last_name , 
                            'company_name' => $value->company_name , 
                            'email' => $value->email,
                            'contact_number' => $value->tel_no,
                            'total_amount' => $sum_amount,
                            'transaction_code' => 'OT-' . Helper::date_format(Carbon::now(), 'ym') . str_pad($value->order_id, 5, "0", STR_PAD_LEFT) . Str::upper(Str::random(3))
                        ]);
                            
                  
                /*if(!$order_transaction){

                    OrderTransaction::where('order_transaction_number' , $value->transaction_number)->update(['order'])
                    $new_order = new OrderTransaction();
                    $new_order->order_transaction_number = $value->transaction_number;
                    $new_order->fname = $value->first_name;
                    $new_order->mname = $value->middle_name;
                    $new_order->lname = $value->last_name;
                    $new_order->company_name = $value->company_name;
                    $new_order->email = $value->email;
                    $new_order->contact_number = $value->tel_no;
                    $new_order->total_amount = $sum_amount;
                    $new_order->transaction_code =  'OT-' . Helper::date_format(Carbon::now(), 'ym') . str_pad($value->order_id, 5, "0", STR_PAD_LEFT) . Str::upper(Str::random(3));
                    $new_order->save();
               }*/
               
            }
            //$details = [
                //'subject' => 'Order Payment Details'
            // ];
            //$job = (new SendMail($details))->delay(now()->addSeconds(10)); 
            //dispatch($job);   
        }
    }
}