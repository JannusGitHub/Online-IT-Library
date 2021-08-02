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
                if(Auth::user()->logdel == 1){
                    return response()->json(['logdel' => "deleted"]);
                }
                else if(Auth::user()->status == 2){
                    return response()->json(['status' => "inactive"]);
                }
                else if(Auth::user()->is_password_changed == 0){
                    return response()->json(['result' => "2"]); // change pass view
                }
                else{
                    session_start();
                    $_SESSION["user_id"] = Auth::user()->id; // use for insert/update/delete of every operation in created_by, last_updated_by & logdel columns
                    $_SESSION["user_level_id"] = Auth::user()->user_level_id; // optional
                    $_SESSION["username"] = Auth::user()->username; // optional
                    // Auth::logout();
                    return response()->json(['result' => "1", 'session' => $_SESSION["user_id"]]);
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
        session_start();

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
                    'created_by' => $_SESSION["user_id"], // to track the user (by its user_id) when he/she add another user
                    'last_updated_by' => $_SESSION['user_id'], // to track who updated/added the user
                    'logdel' => 0, // 0 is default (active)
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                // EMAIL NOTIFICATION
                // if(isset($request->send_email)){
                //     $subject = 'Online IT Library Registration';
                //     $email = $request->email;
                //     $message = 'This is a notification from Online IT Library. Your Online IT Library account was successfully registered.';

                //     dispatch(new SendUserPasswordJob($subject, $message, $request->username, $password, $email));
                // }
                
                DB::commit();
                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                return response()->json(['result' => $e->getMessage()]);
            }
        }
    }


    //============================== CHANGE PASSWORD ==============================
    public function change_pass(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

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
                // DB::beginTransaction();
                try{
                    User::where('id', Auth::user()->id)
                        ->update([
                            'is_password_changed' => 1,
                            'password' => Hash::make($request->new_password),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    // DB::commit();

                    $_SESSION["user_id"] = Auth::user()->id; // use for insert/update/delete of every operation in created_by, last_updated_by & logdel columns
                    $_SESSION["user_level_id"] = Auth::user()->user_level_id; // optional
                    $_SESSION["username"] = Auth::user()->username; // optional
                    return response()->json(['result' => "1", 'session' => $_SESSION["user_id"]]);
                }
                catch(\Exception $e) {
                    // DB::rollback();
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
    public function sign_out(){
        session_start();
        session_unset(); 
        session_destroy();
        Auth::logout();
        return response()->json(['result' => "1"]);
    }


    //============================== GET TOTAL USERS FOR DASHBOARD ==============================
    public function get_total_users(){
        $users = User::where('logdel', 0)->get();

        $totalUsers = count($users);
        return response()->json(['totalUsers' => $totalUsers]);
    }


    //============================== VIEW USERS ==============================
	public function view_users(){
        $users = User::with([
                    'user_level',
                ])->where('logdel', 0)
                ->get();

        return DataTables::of($users)
            ->addColumn('status', function($user){
                $result = "";
                if($user->status == 1){
                    $result .= '<center><span class="badge badge-pill badge-success">Active</span></center>';
                }
                else{
                    $result .= '<center><span class="badge badge-pill badge-secondary">Inactive</span></center>';
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
                    $result .= '</div>'; // div end
                    $result .= '<button type="button" class="btn btn-danger btn-xs ml-1 px-2 actionDeleteUser" user-id="' . $user->id . '" data-toggle="modal" data-target="#modalDeleteUser" data-keyboard="false">
                                    <i class="fas fa-user-minus"></i> 
                                </button>';
                            '</center>';
                    return $result;
            })
            ->rawColumns(['status', 'action']) // to format the added columns(status & action) as html format
            ->make(true);
    }


    //============================== VIEW USERS ARCHIVE ==============================
	public function view_users_archive(){
        $users = User::with([
                    'user_level',
                ])->where('logdel', 1) // 1-deleted, show all deleted users
                ->get();

        return DataTables::of($users)
            ->addColumn('status', function($user){
                $result = "";
                if($user->status == 1){
                    $result .= '<center><span class="badge badge-pill badge-success">Active</span></center>';
                }
                else{
                    $result .= '<center><span class="badge badge-pill badge-secondary">Inactive</span></center>';
                }
                return $result;
            })
            ->addColumn('action', function($user){
                $result = "";
                    $result = '<div><center>'; // div start
                    $result .= '<button type="button" class="btn btn-warning btn-xs ml-1 px-2 actionRestoreUser" user-id="' . $user->id . '" data-toggle="modal" data-target="#modalRestoreUser" data-keyboard="false">
                                    <i class="fas fa-history"></i>
                                </button>';
                    $result .= '</div></center>'; // div end
                    
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
        session_start();

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
                    'last_updated_by' => $_SESSION['user_id'], // to track edit operation
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                
                /*DB::commit();*/
                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => "0", 'tryCatchError' => $e->getMessage()]);
            }
        }
    }


    //============================== DELETE USER ==============================
    public function delete_user(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        $data = $request->all(); // collect all input fields
        
        try{
            User::where('id', $request->user_id)
            ->update([ // The update method expects an array of column and value pairs representing the columns that should be updated.
                'logdel' => 1, // deleted
                'last_updated_by' => $_SESSION['user_id'], // to track edit operation
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            
            /*DB::commit();*/
            return response()->json(['result' => "1"]);
        }
        catch(\Exception $e) {
            DB::rollback();
            // throw $e;
            return response()->json(['result' => "0", 'tryCatchError' => $e->getMessage()]);
        }
    }


    //============================== RESTORE USER ==============================
	public function restore_user(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        try{
            User::where('id', $request->user_id_for_restore)
                ->update([
                    'logdel' => 0, // 0-active
                    'last_updated_by' => $_SESSION['user_id'], // to track who updated/added the user
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            return response()->json(['result' => "1"]);
        }
        catch(\Exception $e) {
            return response()->json(['result' => "0"]);
        }
    }


    //============================== CHANGE USER STAT ==============================
    public function change_user_stat(Request $request){        
        date_default_timezone_set('Asia/Manila');
        session_start();

        $data = $request->all(); // collect all input fields

        $validator = Validator::make($data, [
            'user_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
                User::where('id', $request->user_id)
                    ->update([
                            'status' => $request->status,
                            'last_updated_by' => $_SESSION['user_id'], // to track deactivation operation
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
        session_start();

        $password = 'pmi12345'; // default password

        try{
            User::where('id', $request->user_id)
                ->update([
                        'is_password_changed' => 0,
                        'password' => Hash::make($password),
                        'last_updated_by' => $_SESSION['user_id'], // to track reset password operation
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