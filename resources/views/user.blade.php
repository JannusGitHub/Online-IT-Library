@php $layout = 'layouts.user_layout'; @endphp
@auth
    @php
        if(Auth::user()->user_level_id == 2){
            $layout = 'layouts.user_layout';
        }
    @endphp
    
    {{-- if user's id is not equal to 2 (User) --}}
    @if(Auth::user()->user_level_id != 2)
        <script type="text/javascript">
            window.location = "dashboard";
        </script>
    @endif
@endauth

@auth
    @extends($layout)

    @section('title', 'User')

    @section('content_page')
    
    <style>
        /* Customize search */
        .dataTables_wrapper .row > div{
            flex: 0 0 100%;
            max-width: 100%;
        }
        .dataTables_wrapper .row {
            display: flex;
            flex-direction: column-reverse;
        }

        .dataTables_wrapper .dataTables_filter {
            max-width: 100%;
            text-align: center !important;
            margin: 20px 0 70px;
            position: relative;
        }
        .dataTables_wrapper .dataTables_filter label{
            font-size: 30px;
            color: #343A40;
            display: flex;
            justify-content: center;
        }

        .dataTables_wrapper .dataTables_filter label i{
            margin-top: 6px;
        }


        .dataTables_wrapper .dataTables_filter .totalRecords{
            position: absolute;
            margin-left: clamp(.50%, 1%, 30%);
            margin-left: 1rem;
            left: 63%;
            top: 20%;
            transform: translate(-63%, -20%);
            margin-top: 70px;
            z-index: 20;
        }

        .dataTables_wrapper .dataTables_filter input[type=search]{
            padding: 20px;
            width: 30%;
            box-sizing: border-box;
            border-color: #289cf5;
        }
        
        @media screen and (max-width: 1680px){
            .dataTables_wrapper .dataTables_filter .totalRecords{
                margin-left: .5rem;
            }
        }

        @media screen and (max-width: 1396px){
            .dataTables_wrapper .dataTables_filter .totalRecords{
                margin-left: .2rem;
            }
        }

        @media screen and (max-width: 992px){
            .dataTables_wrapper .dataTables_filter .totalRecords{
                left: 50%;
                margin-left: 0;
                top: 20%;
                transform: translate(-50%, -20%);
                margin-top: 70px;
            }
        }
    </style>

    {{-- <div class="content-wrapper"> --}}
        {{-- <section class="content"> --}}
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary mt-4">
                            
                            <div class="card-body">

                                <br>
                                <div class="table responsive">
                                    <table id="tblWorkloads" class="table table-sm table-bordered table-striped table-hover responsive" style="width: 100%;">
                                        <div class="dataTables_wrapper">
                                            <div class="dataTables_filter">
                                                <div class="totalRecords text-muted"></div>
                                            </div>
                                        </div>

                                        <thead>
                                            <tr>
                                                <th>Status</th>
                                                <th>Name</th>
                                                <th>Work Instruction Title</th>
                                                <th>Description</th>
                                                <th>Uploaded Date</th>
                                                <th>Download the File</th>
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
    @endsection

    @section('js_content')
        <script type="text/javascript">
            //============================== SIGN OUT ==============================
            $(document).ready(function(){
                $("#formSignOut").submit(function(event){
                    event.preventDefault();
                    SignOut();
                });


                $(document).on('click','#tblWorkloads tbody tr',function(e){
                    $(this).closest('tbody').find('tr').removeClass('table-active');
                    $(this).closest('tr').addClass('table-active');
                });


                //============================== GET TOTAL RECORDS USER DASHBOARD ==============================
                function totalRecords(){
                    $.ajax({
                        url: "get_total_records",
                        method: "get",
                        dataType: "json",
                        success: function (response) {
                            $('.totalRecords').text('Total Records ' + + response['totalRecords']);
                            console.log(response['totalRecords']);
                        }
                    });
                }
                totalRecords();


                //============================== VIEW WORKLOADS DATATABLES START ==============================
                dataTableWorkloads = $("#tblWorkloads").DataTable({
                    "processing" : false,
                    "responsive": "true",
                    "serverSide" : true,
                    "responsive": true,
                    "scrollX": true,
                    "order": [[ 0, "desc" ],[ 4, "desc" ]],
                    "language": {
                        "info": "Showing _START_ to _END_ of _TOTAL_ records",
                        "lengthMenu": "Show _MENU_ records",
                        "searchPlaceholder": "Search for work instruction",
                        "search": "<i class='fa fa-search'></i>",
                    },
                    "ajax" : {
                        url: "view_workloads_user_dashboard", // this will be pass in the uri called view_workloads_user_dashboard that handles datatables of view_workloads_user_dashboard() method inside WorkloadController
                    },
                    "columns":[
                        { "data" : "status"},
                        { "data" : "name"},
                        { "data" : "work_instruction_title"},
                        { "data" : "description"},
                        { "data" : "uploaded_date"},
                        { "data" : "file"}
                    ],
                }); // VIEW WORKLOADS DATATABLES END
            });
        </script>
    @endsection
@endauth