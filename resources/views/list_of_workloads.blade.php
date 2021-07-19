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
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>List of Workloads</h1>
                        </div>
                        <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="admin">Dashboard</a></li>
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
                                    <div style="float: right;">                   
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddWorkload" id="btnShowAddWorkloadModal"><i class="fa fa-folder-plus fa-lg"></i> Add Workload</button>
                                    </div> <br><br>
                                    <div class="table responsive">
                                        <table id="tblWorkloads" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Work Instruction Title</th>
                                                    <th>Description</th>
                                                    <th>Status</th>
                                                    <th>File</th>
                                                    <th>Uploaded Date</th>
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


                //============================== VIEW WORKLOADS ==============================
                dataTableUsers = $("#tblWorkloads").DataTable({
                    "processing" : false,
                    "serverSide" : true,
                    "language": {
                        "info": "Showing _START_ to _END_ of _TOTAL_ records",
                        "lengthMenu": "Show _MENU_ records",
                    },
                    "ajax" : {
                        url: "view_workloads", // this will be pass in the uri called view_workloads that handles datatables of view_workloads() method inside WorkloadController
                    },
                    "columns":[
                        { "data" : "id"},
                        { "data" : "name"},
                        { "data" : "work_instruction_title"},
                        { "data" : "description"},
                        { "data" : "status"},
                        { "data" : "file"},
                        { "data" : "uploaded_date"},
                        { "data" : "action", orderable:false, searchable:false}
                    ],
                }); // USERS DATATABLES END
                

                //============================== ADD WORKLOADx   ==============================
                $("#formAddWorkload").submit(function(event){
                    event.preventDefault(); // to stop the form submission
                    AddWorkload();
                });
            });
        </script>
    @endsection
@endauth