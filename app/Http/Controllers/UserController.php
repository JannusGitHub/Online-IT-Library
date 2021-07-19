<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use DataTables;
use App\User;
use App\UserLevel;

class UserController extends Controller
{
    //============================== SIGN IN ==============================
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


    //============================== ADD USER ==============================
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


    //============================== CHANGE PASSWORD ==============================
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


    //============================== SIGN OUT ==============================
    public function sign_out(Request $request){
        Auth::logout();
        return response()->json(['result' => "1"]);
    }


    //============================== VIEW USERS ==============================
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
                $result = '<center><div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                                    <i class="fa fa-lg fa-users-cog"></i> 
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">'; // dropdown-menu start
                if($user->status == 1){
                    $result .= '<button class="dropdown-item text-center actionEditUser" type="button" user-id="' . $user->id . '" data-toggle="modal" data-target="#modalEditUser" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item text-center actionChangeUserStat" type="button" user-id="' . $user->id . '" status="2" data-toggle="modal" data-target="#modalChangeUserStat" data-keyboard="false">Deactivate</button>';

                    $result .= '<button class="dropdown-item text-center actionResetUserPassword" user-id="' . $user->id . '" type="button"  data-toggle="modal" data-target="#modalResetUserPassword" data-keyboard="false">Reset Password</button>';
                }else{
                    $result .= '<button class="dropdown-item text-center actionChangeUserStat" type="button" user-id="' . $user->id . '" status="1" data-toggle="modal" data-target="#modalChangeUserStat" data-keyboard="false">Activate</button>';

                }
                    $result .= '</div>'; // dropdown-menu end
                    $result .= '</div></center>';
                    return $result;
            })
            ->rawColumns(['status', 'action']) // to format the added columns(status & action) as html format
            ->make(true);
    }


    //============================== GET USER BY ID TO EDIT ==============================
    public function get_user_by_id(Request $request){
        $user = User::where('id', $request->user_id)->get(); // get all users where id is equal to the user-id attribute of the dropdown-item of actions dropdown(Edit)
        return response()->json(['user' => $user]);  // pass the $user(variable) to ajax as a response for retrieving and pass the values on the inputs
    }

    public function get_user_list(Request $request){
        $users = User::all();
        return response()->json(['users' => $users]);
    }


    //============================== EDIT USER ==============================
    public function edit_user(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all(); // collect all input fields

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'user_level_id' => 'required|string|max:255',
        ]);

        if($validator->fails()) {
            return response()->json(['validation' => 'hasError', 'error' => $validator->messages()]);
        }
        else{
            /* DB::beginTransaction();
*/
            try{
                User::where('id', $request->user_id)
                ->update([ // The update method expects an array of column and value pairs representing the columns that should be updated.
                    'name' => $request->name,
                    'position' => $request->position,
                    'username' => $request->username,
                    'user_level_id' => $request->user_level_id,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                
                /*DB::commit();*/
                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => "0", 'tryCatchError' => $e]);
            }
        }
    }


    //============================== CHANGE USER STAT ==============================
    public function change_user_stat(Request $request){        
        date_default_timezone_set('Asia/Manila');

        $data = $request->all(); // collect all input fields

        $validator = Validator::make($data, [
            'user_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
                User::where('id', $request->user_id)
                    ->update([
                            'status' => $request->status,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]
                    );
                return response()->json(['result' => "1"]);
        }
        else{
            return response()->json(['validation' => "hasError", 'error' => $validator->messages()]);
        }
    }


    //============================== RESET PASSWORD ==============================
    public function reset_password(Request $request){        
        date_default_timezone_set('Asia/Manila');

        $password = 'pmi12345'; // default password

        try{
            User::where('id', $request->user_id)
                ->update([
                        'is_password_changed' => 0,
                        'password' => Hash::make($password),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]
                );
                return response()->json(['result' => "1"]);
        }
        catch(\Exception $e) {
            DB::rollback();
            // throw $e;
            return response()->json(['result' => "0", "tryCatchError" => $e]);
        } 
    }
}
