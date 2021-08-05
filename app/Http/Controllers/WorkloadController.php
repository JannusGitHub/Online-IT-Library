<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Workload;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use DataTables;

class WorkloadController extends Controller
{
    //============================== VIEW USERS ==============================
	public function add_workload(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();
        
        $data = $request->all();

        $rules = [
            'name' => 'required',
            'work_instruction_title' => 'required',
            'description' => 'required|min:8|max:255',
        ];

        $user_data = array(
            'name' => $request->get('name'),
            'work_instruction_title' => $request->get('work_instruction_title'),
            'description' => $request->get('description'),
        );

        $validator = Validator::make($data, $rules);

        // generate file name
        // $generated_filename = "workloads" . date('YmdHis');

        if($validator->passes()){
            if(isset($request->file)){
                // get original file name
                $original_filename = $request->file('file')->getClientOriginalName();
                        
                // get extension
                $file_extension = $request->file('file')->getClientOriginalExtension();
    
                // concatenate the generated file name with extension
                $workload_filename = $original_filename;
    
                // store in public/file_attachments
                Storage::putFileAs('public/file_attachments', $request->file, $workload_filename);

                Workload::insert([
                    'name' => $request->name,
                    'work_instruction_title' => $request->work_instruction_title,
                    'description' => $request->description,
                    'status' => 1, // done
                    'file' => $original_filename,
                    'uploaded_date' => date('Y-m-d H:i:s'),
                    'created_by' => $_SESSION["user_id"], // to track the user (by its user_id) when he/she add another user
                    'last_updated_by' => $_SESSION['user_id'], // to track who updated/added the user
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                return response()->json(['result' => "1"]);
            }
            else{
                Workload::insert([
                    'name' => $request->name,
                    'work_instruction_title' => $request->work_instruction_title,
                    'description' => $request->description,
                    'status' => 2, // pending
                    'file' => 'N/A',
                    'uploaded_date' => 'N/A',
                    'created_by' => $_SESSION["user_id"], // to track the user (by its user_id) when he/she add another user
                    'last_updated_by' => $_SESSION['user_id'], // to track who updated/added the user
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                return response()->json(['result' => "0"]);
            }
        }
        else{
            return response()->json(['validation' => "hasError", 'error' => $validator->messages()]);
        }
    }


    //============================== GET TOTAL WORKLOADS FOR DASHBOARD ==============================
    public function get_total_workloads(){
        $workloads = Workload::where('logdel', 0)->get();

        $totalWorkloads = count($workloads);
        return response()->json(['totalWorkloads' => $totalWorkloads]);
    }


    //============================== GET TOTAL WORKLOADS FOR DASHBOARD ==============================
    public function get_total_records(){
        $records = Workload::where('logdel', 0)->where('status', 1)->get();

        $totalRecords = count($records);
        return response()->json(['totalRecords' => $totalRecords]);
    }


    //============================== VIEW WORKLOADS ==============================
	public function view_workloads(){
        $workloads = Workload::get()
                    ->where('logdel', 0); // 0-active, show all users
                
        return DataTables::of($workloads)
            ->addColumn('status', function($workload){
                $result = "";
                if($workload->status == 1){
                    $result .= '<center><span class="badge badge-pill badge-success">Done</span></center>';
                }
                else{
                    $result .= '<center><span class="badge badge-pill badge-danger">Pending</span></center>';
                }
                return $result;
            })
            ->addColumn('file', function($workload){
                if($workload->file != 'N/A'){
                    $result =   '<center><a href="download_attached_document/'. $workload->id . '" title="Click to download file">
                    <button type="button" class="btn btn-sm btn-primary"><i text-dark class="fas fa-file-download"></i> Download</button>
                                    
                                </a></center>';
                    return $result;
                }else{
                    return $workload->file;
                }
            })
            ->addColumn('action', function($workload){
                $result = '<center><div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-xs text-center actionEditWorkload" type="button" workload-id="' . $workload->id . '" data-toggle="modal" data-target="#modalEditWorkload" aria-haspopup="true" aria-expanded="false" title="Action">
                                            <i class="fa fa-lg fa-edit"></i> 
                                        </button>
                                    </div>';
                $result .= '<button type="button" class="btn btn-danger btn-xs ml-1 px-2 actionDeleteWorkload" workload-id="' . $workload->id . '" data-toggle="modal" data-target="#modalDeleteWorkload" data-keyboard="false">
                                <i class="fas fa-lg fa-trash-alt"></i>
                            </button>';
                            '</center>'; // dropdown-menu start
                return $result;
            })
            ->rawColumns(['status', 'file', 'action']) // to format the added columns(status, file & action) as html format
            ->make(true);
    }


    //============================== VIEW WORKLOADS ARCHIVE ==============================
	public function view_workloads_archive(){
        $workloads = Workload::where('logdel', 1) // 1-deleted, show all deleted users
                ->get();

        return DataTables::of($workloads)
            ->addColumn('status', function($workload){
                $result = "";
                if($workload->status == 1){
                    $result .= '<center><span class="badge badge-pill badge-success">Done</span></center>';
                }
                else{
                    $result .= '<center><span class="badge badge-pill badge-danger">Pending</span></center>';
                }
                return $result;
            })
            ->addColumn('action', function($workload){
                $result = "";
                    $result = '<div><center>'; // div start
                    $result .= '<button type="button" class="btn btn-warning btn-xs ml-1 px-2 actionRestoreWorkload" workload-id="' . $workload->id . '" data-toggle="modal" data-target="#modalRestoreWorkload" data-keyboard="false">
                                    <i class="fas fa-history"></i>
                                </button>';
                    $result .= '</div></center>'; // div end
                    
                    return $result;
            })
            ->rawColumns(['status', 'action']) // to format the added columns(status & action) as html format
            ->make(true);
    }


    //============================== DOWNLOAD FILE ==============================
    public function download_attached_document(Request $request, $id)
    {
        $workloads = Workload::where('id', $id)->first();

        $file =  storage_path() . "/app/public/file_attachments/" . $workloads->file;

        return Response::download($file, $workloads->file);  
    }


    //============================== GET WORKLOAD BY ID TO EDIT ==============================
    public function get_workload_by_id(Request $request){
        $workload = Workload::where('id', $request->workload_id)->get(); // get all workloads where id is equal to the workload-id attribute
        return response()->json(['workload' => $workload]);  // pass the $workload(variable) to ajax as a response for retrieving and pass the values on the inputs
    }


    //============================== EDIT WORKLOAD ==============================
    public function edit_workload(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        $data = $request->all(); // collect all input fields

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'work_instruction_title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        if($validator->fails()) {
            return response()->json(['validation' => 'hasError', 'error' => $validator->messages()]);
        }
        else{
            if(isset($request->file)){
                // get original file name
                $original_filename = $request->file('file')->getClientOriginalName();
                        
                // get extension
                $file_extension = $request->file('file')->getClientOriginalExtension();

                $workload_filename = $original_filename;

                // store in public/file_attachments
                Storage::putFileAs('public/file_attachments', $request->file, $workload_filename);

                try{
                    Workload::where('id', $request->workload_id)
                    ->update([ // The update method expects an array of column and value pairs representing the columns that should be updated.
                        'name' => $request->name,
                        'work_instruction_title' => $request->work_instruction_title,
                        'description' => $request->description,
                        'status' => 1, // done
                        'file' => $original_filename,
                        'uploaded_date' => date('Y-m-d H:i:s'),
                        'last_updated_by' => $_SESSION['user_id'], // to track edit operation
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    
                    return response()->json(['result' => "1"]);
                }
                catch(\Exception $e) {
                    // throw $e;
                    return response()->json(['result' => "0", 'tryCatchError' => $e]);
                }
                return response()->json(['result' => "1"]);
            }
            else{
                Workload::where('id', $request->workload_id)
                ->update([ // The update method expects an array of column and value pairs representing the columns that should be updated.
                    'name' => $request->name,
                    'work_instruction_title' => $request->work_instruction_title,
                    'description' => $request->description,
                    'status' => 2, // pending
                    'file' => 'N/A',
                    'uploaded_date' => 'N/A',
                    'last_updated_by' => $_SESSION['user_id'], // to track edit operation
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                return response()->json(['result' => "0"]);
            }
            
        }
    }


    //============================== DELETE WORKLOAD ==============================
    public function delete_workload(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        $data = $request->all(); // collect all input fields
        
        try{
            Workload::where('id', $request->workload_id_for_delete)
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
	public function restore_workload(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();

        try{
            Workload::where('id', $request->workload_id_for_restore)
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


    //============================== VIEW WORKLOADS FOR USER DASHBOARD ==============================
	public function view_workloads_user_dashboard(){
        $workloads = Workload::where('logdel', 0)->where('status', 1)
                    ->get();
                
        return DataTables::of($workloads)
            ->addColumn('status', function($workload){
                $result = "";
                if($workload->status == 1){
                    $result .= '<center><span class="badge badge-pill badge-success">Done</span></center>';
                }
                else{
                    $result .= '<center><span class="badge badge-pill badge-danger">Pending</span></center>';
                }
                return $result;
            })
            ->addColumn('file', function($workload){
                if($workload->file != 'N/A'){
                    $result =   '<center><a href="download_attached_document/'. $workload->id . '" title="Click to download file">
                                    <button type="button" class="btn btn-sm btn-primary"><i text-dark class="fas fa-file-download"></i> Download</button>
                                    
                                </a></center>';
                    return $result;
                }else{
                    return $workload->file;
                }
            })
            ->rawColumns(['status', 'file']) // to format the added columns(status & file) as html format
            ->make(true);
    }


    
}
