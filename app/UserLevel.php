<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class UserLevel extends Model
{
    protected $table = 'user_levels';
    // protected $connection = 'mysql';
    public function users(){
        return $this->hasMany(User::class, 'user_level_id', 'id'); 
    }
}
