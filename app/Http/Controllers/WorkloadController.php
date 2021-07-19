<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Workload;
use Illuminate\Support\Facades\Validator;
use DataTables;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\Storage;

class WorkloadController extends Controller
{
    //============================== VIEW USERS ==============================
	public function add_workload(Request $request){
        date_default_timezone_set('Asia/Manila');
        
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

        // On Going 07-19-2021 - By Jannus
        // make <a> href tag then create a function on the controller (ApplicationController)

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
                    'uploaded_date' => date('Y-m-d H:i:s').diffForHumans(),
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


    //============================== VIEW WORKLOADS ==============================
	public function view_workloads(){
        $workloads = Workload::get();
                

        return DataTables::of($workloads)
            ->addColumn('status', function($workload){
                $result = "";
                if($workload->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Done</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Pending</span>';
                }
                return $result;
            })
            ->addColumn('action', function($workload){
                $result = '<center><div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                                    <i class="fa fa-lg fa-users-cog"></i> 
                                </button>
                                <div class="dropdown-menu dropdown-menu-right"></center>'; // dropdown-menu start
                // if($workload->status == 1){
                //     $result .= '<button class="dropdown-item text-center actionEditUser" type="button" user-id="' . $workload->id . '" data-toggle="modal" data-target="#modalEditUser" data-keyboard="false">Edit</button>';

                //     $result .= '<button class="dropdown-item text-center actionChangeUserStat" type="button" user-id="' . $workload->id . '" status="2" data-toggle="modal" data-target="#modalChangeUserStat" data-keyboard="false">Deactivate</button>';

                //     $result .= '<button class="dropdown-item text-center actionResetUserPassword" user-id="' . $workload->id . '" type="button"  data-toggle="modal" data-target="#modalResetUserPassword" data-keyboard="false">Reset Password</button>';
                // }else{
                //     $result .= '<button class="dropdown-item text-center actionChangeUserStat" type="button" user-id="' . $workload->id . '" status="1" data-toggle="modal" data-target="#modalChangeUserStat" data-keyboard="false">Activate</button>';

                // }
                //     $result .= '</div>'; // dropdown-menu end
                //     $result .= '</div></center>';
                
                    return $result;
            })
            ->rawColumns(['status', 'action']) // to format the added columns(status & action) as html format
            ->make(true);
    }
}
