<?php

namespace App\Laravel\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Laravel\Traits\DateFormatter;

use Carbon, Helper, Str;

class Customer extends Authenticatable{

    use Notifiable,SoftDeletes,DateFormatter;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "customer";

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

    protected $fillable = ['fname','lname','mname', 'email', 'contact_number',
        'password'];


    public function getNameAttribute(){
        return Str::title("{$this->fname} ".(strlen($this->mname) > 0 ? Str::title($this->mname): NULL)." {$this->lname} ");
    }

    public function getFullNameAttribute(){
        return Str::title("{$this->fname} {$this->lname} ");
    }

}
