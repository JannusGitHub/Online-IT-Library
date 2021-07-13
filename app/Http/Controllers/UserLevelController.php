<?php

namespace App\Http\Controllers;
use App\UserLevel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UserLevelController extends Controller
{
    public function get_user_levels(Request $request){
        $user_levels = UserLevel::all();
        return response()->json(['user_levels' => $user_levels]);
    }

    // public function get_user_levels(Request $request){
    //     $user_levels = UserLevel::with(['users'])->get();
    //     return response()->json(['user_levels' => $user_levels]);
    // }
}
