<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use DataTables;
use App\User;

class UserController extends Controller
{
    // Sign In
    public function sign_in(Request $request){
        $user_data = array(
            'username' => $request->get('username'),
            'password' => $request->get('password'),
            // 'status' => "1"
        );

        $validator = Validator::make($user_data, [
            'username' => 'required|',
            'password' => 'required|alphaNum|min:8'
        ]);

        if($validator->passes()){
            if(Auth::attempt($user_data)){
                if(Auth::user()->status == 2){
                    return response()->json(['status' => "inactive"]);
                }
                else if(Auth::user()->is_password_changed == 0){
                    return response()->json(['result' => "2"]); // change pass view
                }
                else{
                    return response()->json(['result' => "1"]); // user dashboard view
                }
            }
            else{
                return response()->json(['result' => "0", 'error_message' => 'We do not recognize your username and/or password. Please try again.', 'error' => $validator->messages()]);
            }
        }
        else{
            return response()->json(['validation' => "hasError", 'error' => $validator->messages()]);
        }
    }


    // Add User
    public function add_user(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();
        $password = 'pmi12345'; // default password

        $rules = [
            'name' => 'required|string|max:255',
            'position' => 'required',
            'username' => 'required|string|max:255|unique:users',
            'user_level_id' => 'required|string|max:255',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(['validation' => 'hasError', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();
            try{
                $user_id = User::insert([
                    'name' => $request->name,
                    'position' => $request->position,
                    'username' => $request->username,
                    'password' => Hash::make($password),
                    'is_password_changed' => 0,
                    'status' => 1,
                    'user_level_id' => $request->input('user_level_id'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                // EMAIL NOTIFICATION
                // if(isset($request->send_email)){
                //     $subject = 'PATS User Registration';
                //     $email = $request->email;
                //     $message = 'This is a notification from PATS. Your PATS user account was successfully registered.';

                //     dispatch(new SendUserPasswordJob($subject, $message, $request->username, $password, $email));
                // }
                
                DB::commit();
                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                return response()->json(['result' => $e]);
            }
        }
    }


    // Change Password
    public function change_pass(Request $request){        
        date_default_timezone_set('Asia/Manila');
        $user_data = array(
            'username' => $request->username,
            'password' => $request->password,
            'new_password' => $request->new_password,
            'confirm_password' => $request->confirm_password,
        );

        $validator = Validator::make($user_data, [
            'username' => 'required',
            'password' => 'required|alphaNum|min:8',
            'new_password' => 'required|alphaNum|min:8|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|alphaNum|min:8'
        ]);

        if($validator->passes()){
            if(Auth::attempt($user_data)){
                DB::beginTransaction();
                try{
                    User::where('id', Auth::user()->id)
                        ->update([
                            'is_password_changed' => 1,
                            'password' => Hash::make($request->new_password),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    DB::commit();
                    return response()->json(['result' => "1"]);
                }
                catch(\Exception $e) {
                    DB::rollback();
                    return response()->json(['result' => "0"]);
                }  
                
                // return response()->json(['result' => 1]);
            }
            else{
                return response()->json(['result' => "0", 'error_message' => 'Changing password failed. Please try again']);
            }
        }
        else{
            return response()->json(['validation' => "hasError", 'error' => $validator->messages()]);
        }
    }


    // Sign Out
    public function sign_out(Request $request){
        Auth::logout();
        return response()->json(['result' => "1"]);
    }


    //View Users
	public function view_users(){
        $users = User::with([
                    'user_level',
                ])
                ->get();

        return DataTables::of($users)
            ->addColumn('status', function($user){
                $result = "";
                if($user->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }
                return $result;
            })
            ->addColumn('action', function($user){
                $result = "";
                $result .= '<button class="dropdown-item aEditUser" type="button" user-id="' . $user->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditUser" data-keyboard="false">Edit</button>';
                return $result;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}
