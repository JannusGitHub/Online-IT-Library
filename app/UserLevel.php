<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class UserLevel extends Model
{
    protected $table = 'user_levels';
    // protected $connection = 'mysql';

    /* the parameter accepts 3 arguments which is the related model as first param, second param is the id of related model and last is the FK 
    on this table that you want to refers to, which is the main model(Parent) */
    public function users(){
        return $this->hasMany(User::class, 'user_level_id', 'id');
    }
}
