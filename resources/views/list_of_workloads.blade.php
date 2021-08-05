@php $layout = 'layouts.user_layout'; @endphp
@auth
    @php
        if(Auth::user()->user_level_id == 1){
            $layout = 'layouts.admin_layout';
        }
        else if(Auth::user()->user_level_id == 2){
            $layout = 'layouts.user_layout';
        }
    @endphp
@endauth

@auth
    @extends($layout)
    @section('title', 'List of Workloads')

    @section('content_page')
    <style>
        table.dataTable td:nth-child(4) {
            max-width: 300px;
        }
        table.dataTable td:nth-child(7) {
            max-width: 200px;
        }
        table.dataTable td  {
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }
    </style>
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>List of Workloads</h1>
                        </div>
                        <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                                <li class="breadcrumb-item active">List of Workloads</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">List of Workloads</h3>
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="workloads-tab" data-toggle="tab" href="#workloads" role="tab" aria-controls="workloads" aria-selected="true">Workloads Tab</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="workloads-archive-tab" data-toggle="tab" href="#workloadsArchive" role="tab" aria-controls="archive" aria-selected="false">Archive Tab</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="workloads" role="tabpanel" aria-labelledby="workloads-tab">
                                            <div class="text-right mt-4">                   
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddWorkload" id="btnShowAddWorkloadModal"><i class="fa fa-plus fa-md"></i> New Workload</button>
                                            </div><br>
                                            <div class="table responsive">
                                                <table id="tblWorkloads" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            {{-- <th>ID</th> --}}
                                                            <th>Status</th>
                                                            <th>Name</th>
                                                            <th>Work Instruction Title</th>
                                                            <th>Description</th>
                                                            <th>Uploaded Date</th>
                                                            <th>File</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="workloadsArchive" role="tabpanel" aria-labelledby="workloads-archive-tab">
                                            <div class="table responsive mt-5">
                                                <table id="tblWorkloadsArchive" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            {{-- <th>ID</th> --}}
                                                            <th>Status</th>
                                                            <th>Name</th>
                                                            <th>Work Instruction Title</th>
                                                            <th>Description</th>
                                                            <th>Uploaded Date</th>
                                                            <th>File</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>          
            </section>
        </div>


        <!-- ADD MODAL START -->
        <div class="modal fade" id="modalAddWorkload">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h4 class="modal-title"><i class="fa fa-folder-plus"></i> Add Workload</h4>
                        <button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="formAddWorkload" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Name of IT Specialist</label>
                                        <input type="text" class="form-control" name="name" id="txtAddWorkloadName">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Work Instruction Title</label>
                                        <input type="text" class="form-control" name="work_instruction_title" id="txtAddWorkloadWorkInstructionTitle">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea type="text" class="form-control" name="description" id="txtAddWorkloadDescription"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>File</label>
                                        <input type="file" class="form-control" name="file" id="fileAddWorkloadFile">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" id="btnAddWorkload" class="btn btn-primary"><i id="iBtnAddWorkloadIcon" class="fa fa-check"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- ADD MODAL END -->


        <!-- EDIT WORKLOAD MODAL START -->
        <div class="modal fade" id="modalEditWorkload" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h4 class="modal-title"><i class="fa fa-user"></i> Edit Workload</h4>
                        <button type="button" style="color: #fff" class="close resetModalValue" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="formEditWorkload">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="hidden" class="form-control" name="workload_id" id="txtEditWorkloadId">
                                    <div class="form-group">
                                        <label>Name of IT Specialist</label>
                                        <input type="text" class="form-control" disabled name="name" id="txtEditWorkloadName">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Work Instruction Title</label>
                                        <input type="text" class="form-control" disabled name="work_instruction_title" id="txtEditWorkloadWorkInstructionTitle">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea type="text" class="form-control" disabled name="description" id="txtEditWorkloadDescription"></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>File</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control inputText" disabled id="txtEditWorkloadFile"> <!-- removed the name="file" coz the input type file that has attr(name) with a value of file will be use to the controller $request->file -->
                                            <input type="file" class="form-control d-none inputFile" name="file" id="fileEditWorkloadFile">
                                        </div>
                                        <input type="checkbox" id="chkEditFile" name="chkFile">
                                        <label class="font-weight-normal" for="chkEditFile">Edit attachment</label>
                                    </div>  
                                    
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default resetModalValue" data-dismiss="modal">Close</button>
                            <button type="submit" id="btnEditWorkload" class="btn btn-primary d-none"><i id="iBtnEditWorkloadIcon" class="fa fa-check"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- EDIT WORKLOAD MODAL END -->


        <!-- DELETE WORKLOAD MODAL START -->
        <div class="modal fade" id="modalDeleteWorkload">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h4 class="modal-title"><i class="fa fa-user"></i> Delete User</h4>
                        <button type="button" style="color: #fff" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="formDeleteWorkload">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <label id="lblDeleteWorkload" class="text-secondary mt-2">Are you sure you want to delete this workload?</label>
                                <input type="hidden" class="form-control" name="workload_id_for_delete" id="txtDeleteWorkloadId">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" id="btnDeleteWorkload" class="btn btn-primary"><i id="iBtnDeleteWorkloadIcon" class="fa fa-check"></i> Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- DELETE WORKLOAD MODAL END -->


        <!-- RESTORE MODAL START -->
        <div class="modal fade" id="modalRestoreWorkload">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h4 class="modal-title"><i class="fa fa-user"></i> Restore Workload</h4>
                        <button type="button" style="color: #fff" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="formRestoreWorkload">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <label id="lblRestoreWorkload" class="text-secondary mt-2">Are you sure you want to restore this workload?</label>
                                <input type="hidden" class="form-control" name="workload_id_for_restore" id="txtRestoreWorkloadId">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" id="btnRestoreWorkload" class="btn btn-primary"><i id="iBtnRestoreWorkloadIcon" class="fa fa-check"></i> Restore</button>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- RESTORE MODAL END -->
    @endsection

    @section('js_content')
        <script type="text/javascript">
            $(document).ready(function () {
                //Initialize Select2 Elements
                $('.select2').select2();

                //Initialize Select2 Elements
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                });


                //============================== VIEW WORKLOADS DATATABLES START ==============================
                dataTableWorkloads = $("#tblWorkloads").DataTable({
                    "processing" : false,
                    "serverSide" : true,
                    "responsive": true,
                    "scrollX": true,
                    "order": [[ 0, "desc" ],[ 4, "desc" ]],
                    "language": {
                        "info": "Showing _START_ to _END_ of _TOTAL_ records",
                        "lengthMenu": "Show _MENU_ records",
                    },
                    "ajax" : {
                        url: "view_workloads", // this will be pass in the uri called view_workloads that handles datatables of view_workloads() method inside WorkloadController
                    },
                    "columns":[
                        // { "data" : "id"},
                        { "data" : "status"},
                        { "data" : "name"},
                        { "data" : "work_instruction_title"},
                        { "data" : "description"},
                        { "data" : "uploaded_date"},
                        { "data" : "file"},
                        { "data" : "action", orderable:false, searchable:false}
                    ],
                }); // VIEW WORKLOADS DATATABLES END


                //============================== VIEW WORKLOADS ARCHIVE DATATABLES START ==============================
                dataTableWorkloadsArchive = $("#tblWorkloadsArchive").DataTable({
                    "processing" : false,
                    "serverSide" : true,
                    "responsive": true,
                    "order": [[ 0, "desc" ],[ 4, "desc" ]],
                    "language": {
                        "info": "Showing _START_ to _END_ of _TOTAL_ records",
                        "lengthMenu": "Show _MENU_ records",
                    },
                    "ajax" : {
                        url: "view_workloads_archive", // this will be pass in the uri called view_workloads that handles datatables of view_workloads() method inside WorkloadController
                    },
                    "columns":[
                        // { "data" : "id"},
                        { "data" : "status"},
                        { "data" : "name"},
                        { "data" : "work_instruction_title"},
                        { "data" : "description"},
                        { "data" : "uploaded_date"},
                        { "data" : "file"},
                        { "data" : "action", orderable:false, searchable:false}
                    ],
                }); // VIEW WORKLOADS ARCHIVE DATATABLES END


                //============================== ADD WORKLOAD ==============================
                $("#formAddWorkload").submit(function(event){
                    event.preventDefault(); // to stop the form submission
                    AddWorkload();
                    dataTableWorkloads.draw(); // reload datatables asynchronously
                });


                //============================== EDIT WORKLOAD ==============================
                $(document).on('click', '.actionEditWorkload', function(){
                    // the workload-id(attr) is inside the datatables of WorkloadController that will be use to collect the workload-id
                    let workloadId = $(this).attr('workload-id'); 
                    
                    // after clicking the actionEditWorkload(button) the workloadId will be pass to the txtEditUserId(input=hidden) and when the form is submitted this will be pass to ajax and collect workload-id that will be use to query the workload-id in the WorkloadController to update the Workload
                    $("#txtEditWorkloadId").val(workloadId);
                    
                    // COLLECT THE workloadId AND PASS TO INPUTS, BASED ON THE CLICKED ROWS
                    // GetWorkloadByIdToEdit() function is inside Workload.js and pass the workloadId as an argument when passing the ajax that will be use to query the workload-id of get_workload_by_id() method inside WorkloadController and pass the fetched workload based on that query as $workload(variable) to pass the values in the inputs of modalEditWorklaod and also to validate the fetched values, inside GetUserByIdToEdit under Workload.js
                    GetWorkloadByIdToEdit(workloadId);
                    
                    // VALIDATION(errors)
                    $("#txtEditWorkloadId").removeClass('is-invalid');
                    $("#txtEditWorkloadId").attr('title', '');
                    $("#txtEditWorkloadWorkInstructionTitle").removeClass('is-invalid');
                    $("#txtEditWorkloadWorkInstructionTitle").attr('title', '');
                    $("#txtEditWorkloadDescription").removeClass('is-invalid');
                    $("#txtEditWorkloadDescription").attr('title', '');
                    $("#fileEditWorkloadFile").removeClass('is-invalid');
                    $("#fileEditWorkloadFile").attr('title', '');
                });
                // The EditWorkload(); function is inside public/js/my_js/Workload.js
                // after the submission, the ajax request will pass the formEditWorkload(form) of its data(input) in the uri(edit_workload)
                // then the controller will handle that uri to use specific method called edit_workload() inside WorkloadController
                $("#formEditWorkload").submit(function(event){
                    event.preventDefault();
                    EditWorkload();
                });
                // Let user reset the modal values when the close btn is clicked like hiding by removing the class, set the file value to null and uncheck the checkbox
                $('.resetModalValue').click(function(){
                    resetModalValue();
                });


                //============================== DELETE WORKLOAD ==============================
                // actionDeleteWorkload is generated by datatables and open the modalDeleteWorkload(modal) to collect the id of the specified rows
                $(document).on('click', '.actionDeleteWorkload', function(){
                    // the workload-id(attr) is inside the datatables of WorkloadController that will be use to collect the workload-id
                    let workloadId = $(this).attr('workload-id'); 
                    
                    // after clicking the actionDeleteWorkload(button) the workloadId will be pass to the txtDeleteWorkloadId(input type hidden) and when the form is submitted this will be pass to ajax and collect workload-id that will be use to query the workload-id in the WorkloadController to delete the workload
                    $("#txtDeleteWorkloadId").val(workloadId);
                });
                $("#formDeleteWorkload").submit(function(event){
                    event.preventDefault();
                    DeleteWorkload();
                    dataTableWorkloads.draw(); // reload datatable of workloads after deletion
                    dataTableWorkloadsArchive.draw(); // reload datatable of workloads archive after deletion
                });


                //============================== RESTORE USER ==============================
                // actionRestoreWorkload is generated by datatables and open the modalRestoreWorkload(modal) to collect the id of the specified rows
                $(document).on('click', '.actionRestoreWorkload', function(){
                    // the workload-id(attr) is inside the datatables of WorkloadController that will be use to collect the workload-id
                    let workloadId = $(this).attr('workload-id');
                    
                    // after clicking the actionRestoreWorkload(button) the workloadId will be pass to the txtRestoreWorkloadId(input=hidden) and when the form is submitted this will be pass to ajax and collect workload-id that will be use to query the workload-id in the WorkloadController to restore the workload
                    $("#txtRestoreWorkloadId").val(workloadId);
                });
                $("#formRestoreWorkload").submit(function(event){
                    event.preventDefault();
                    RestoreWorkload();
                    dataTableWorkloadsArchive.draw(); // reload datatable of workloads archive after restoration
                    dataTableWorkloads.draw(); // reload datatable of workloads after restoration
                });


                //============================== SIGN OUT ==============================
                $(document).ready(function(){
                    $("#formSignOut").submit(function(event){
                        event.preventDefault();
                        SignOut();
                    });
                });
            });
        </script>
    @endsection
@endauth