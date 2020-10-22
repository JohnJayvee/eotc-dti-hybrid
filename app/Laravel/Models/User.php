<?php

namespace App\Laravel\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Laravel\Traits\DateFormatter;

use Carbon, Helper, Str;

class User extends Authenticatable{

    use Notifiable,SoftDeletes,DateFormatter;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = "user";

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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = [
        'name'
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    /*public function getJWTIdentifier()
    {
        return $this->getKey();
    }*/

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    /*public function getJWTCustomClaims()
    {
        return [];
    }*/

    public function getNameAttribute(){
        return Str::title("{$this->lname}".(strlen($this->mname) > 0 ? " ".Str::title($this->mname): NULL)." {$this->fname}");
    }

    public function getFullNameAttribute(){
        return Str::title("{$this->fname} {$this->lname} ");
    }

    public function department(){
        return $this->BelongsTo("App\Laravel\Models\Department",'department_id','id');
    }
    public function application(){
        return $this->BelongsTo("App\Laravel\Models\Application",'application_id','id');
    }

}
