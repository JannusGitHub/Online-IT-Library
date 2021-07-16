<?php

namespace App;
use App\UserLevel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'position', 'username', 'user_level_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $table = 'users';

    // Inverse Relationship means) 
    /* the parameter in inverse relationship accepts 3 arguments which is the related model as first param, second param is the id 
    of related model and last is the FK on this table that you want to refers to, which is the main model(Parent) */
    // public function user_level(){
    //     return $this->hasOne(UserLevel::class, 'id', 'user_level_id');
    // }
    
    // first param is the related model and the second param is the FK that you want to reference to, which is the main model(Parent)
    // what is the FK that you want to reference to the related model? user_level_id
    public function user_level(){
        return $this->belongsTo(UserLevel::class, 'user_level_id');
    }
}