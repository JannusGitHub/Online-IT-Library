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

    @section('title', 'User')

    @section('content_page')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>User Management</h1>
                    </div>
                    <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="admin">Dashboard</a></li>
                            <li class="breadcrumb-item active">User Management</li>
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
                                <h3 class="card-title">User Management</h3>
                            </div>
                            <div class="card-body">
                                <div style="float: right;">                   
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddUser" id="btnShowAddUserModal"><i class="fa fa-user-plus"></i> Add User</button>
                                </div> <br><br>
                                <div class="table responsive">
                                    <table id="tblUsers" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                                        <thead>
                                            <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Username</th>
                                            <th>User Level</th>
                                            <th>Status</th>
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
    <div class="modal fade" id="modalAddUser">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h4 class="modal-title"><i class="fa fa-user-plus"></i> Add User</h4>
                    <button type="button" style="color: #fff;" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="formAddUser">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" id="txtAddUserName">
                                </div>
                                
                                <div class="form-group">
                                    <label>Position</label>
                                    <input type="text" class="form-control" name="position" id="txtAddUserPosition">
                                </div>
                                
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control" name="username" id="txtAddUserUserName">
                                </div>

                                <div class="form-group">
                                    <label>User Level</label>
                                    <select class="form-control select2bs4 selectUserLevel" name="user_level_id" id="selAddUserLevel" style="width: 100%;">
                                    <!-- Code generated -->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" id="btnAddUser" class="btn btn-primary"><i id="iBtnAddUserIcon" class="fa fa-check"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- ADD MODAL END -->

    <!-- EDIT MODAL START -->
    <div class="modal fade" id="modalEditUser">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h4 class="modal-title"><i class="fa fa-user"></i> Edit User</h4>
                    <button type="button" style="color: #fff" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="formEditUser">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <input type="hidden" class="form-control" name="user_id" id="txtEditUserId">
                                <div class="form-group">
                                <label>Name</label>
                                    <input type="text" class="form-control" name="name" id="txtEditUserName">
                                </div>

                                <div class="form-group">
                                <label>Position</label>
                                    <input type="text" class="form-control" name="position" id="txtEditUserPosition">
                                </div>

                                <div class="form-group">
                                <label>Username</label>
                                    <input type="text" class="form-control" name="username" id="txtEditUserUserName">
                                </div>

                                <div class="form-group">
                                <label>User Level</label>
                                    <select class="form-control select2bs4 selectUserLevel" name="user_level_id" id="selEditUserLevel" style="width: 100%;">
                                    <!-- Code generated -->
                                    </select>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" id="btnEditUser" class="btn btn-primary"><i id="iBtnEditUserIcon" class="fa fa-check"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- EDIT MODAL END -->

    <!-- CHANGE USER STAT MODAL START -->
    <div class="modal fade" id="modalChangeUserStat">
        <div class="modal-dialog">
            <div class="modal-content modal-sm">
                <div class="modal-header bg-dark">
                    <h4 class="modal-title" id="h4ChangeUserTitle"><i class="fa fa-user"></i> Change Status</h4>
                    <button type="button" style="color: #fff" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="formChangeUserStat">
                    @csrf
                    <div class="modal-body">
                        <label id="lblChangeUserStatLabel"></label>
                        <input type="hidden" name="user_id" placeholder="User Id" id="txtChangeUserStatUserId">
                        <input type="hidden" name="status" placeholder="Status" id="txtChangeUserStatUserStat">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <button type="submit" id="btnChangeUserStat" class="btn btn-primary"><i id="iBtnChangeUserStatIcon" class="fa fa-check"></i> Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div> <!-- CHANGE USER STAT MODAL END -->

    <!-- RESET PASSWORD MODAL START -->
    <div class="modal fade" id="modalResetUserPassword">
        <div class="modal-dialog">
            <div class="modal-content modal-sm">
                <div class="modal-header bg-dark">
                    <h4 class="modal-title"><i class="fa fa-user"></i> Reset User Password</h4>
                    <button type="button" style="color: #fff" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="formResetUserPassword">
                    @csrf
                    <div class="modal-body">
                        <label>Are you sure to reset password?</label>
                        <input type="hidden" name="user_id" placeholder="User Id" id="txtResetUserPasswordUserId">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <button type="submit" id="btnResetUserPassword" class="btn btn-primary"><i id="iBtnResetUserPasswordIcon" class="fa fa-check"></i> Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- RESET PASSWORD MODAL END -->

    <!-- UPLOAD PDF MODAL START -->
    <div class="modal fade" id="modalImportUser">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h4 class="modal-title"><i class="fa fa-file-excel"></i>Upload PDF</h4>
                    <button type="button" style="color: #fff" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" id="formImportUser" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                <label>File</label>
                                    <input type="file" class="form-control" name="import_file" id="fileImportUser">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" id="btnImportUser" class="btn btn-primary"><i id="iBtnImportUserIcon" class="fa fa-check"></i>Upload PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- UPLOAD PDF MODAL END -->
    @endsection

    <!--     {{-- JS CONTENT --}} -->
    @section('js_content')
    
    <script type="text/javascript">
        let dataTableUsers;
        let arrSelectedUsers = [];

        $(document).ready(function () {
            bsCustomFileInput.init();
            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            $(document).on('click','#tblUsers tbody tr',function(e){
                $(this).closest('tbody').find('tr').removeClass('table-active');
                $(this).closest('tr').addClass('table-active');
            });

            // USERS DATATABLES START
            // The GetUserLevel(); function is inside public/js/my_js/UserLevel.js
            // this will fetch <option> based on the uri called get_user_levels
            // then the controller will handle that uri to use specific method called get_user_levels() inside UserLevelController
            GetUserLevel($(".selectUserLevel"));

            dataTableUsers = $("#tblUsers").DataTable({
                "processing" : false,
                "serverSide" : true,
                "language": {
                    "info": "Showing _START_ to _END_ of _TOTAL_ records",
                    "lengthMenu":     "Show _MENU_ records",
                },
                "ajax" : {
                    url: "view_users", // this will be pass in the uri called view_users that handles datatables of view_users() method inside UserController
                },
                "columns":[
                    { "data" : "id" },
                    { "data" : "name" },
                    { "data" : "position" },
                    { "data" : "username" },
                    { "data" : "user_level.name" },
                    { "data" : "status" },
                    { "data" : "action", orderable:false, searchable:false }
                ],
            }); // USERS DATATABLES END

            
            ///============================== ADD USER ==============================
            // The AddUser(); function is inside public/js/my_js/User.js
            // after the submission, the ajax request will pass the formAddUser(form) of data(input) in the uri(add_user)
            // then the controller will handle that uri to use specific method called add_user() inside UserController
            $("#formAddUser").submit(function(event){
                event.preventDefault(); // to stop the form submission
                AddUser();
            });

            // VALIDATION(remove errors)
            $("#btnShowAddUserModal").click(function(){
                $("#txtAddUserName").removeClass('is-invalid');
                $("#txtAddUserName").attr('title', '');
                $("#txtAddUserUserName").removeClass('is-invalid');
                $("#txtAddUserUserName").attr('title', '');
                $("#txtAddUserPosition").removeClass('is-invalid');
                $("#txtAddUserPosition").attr('title', '');
                $("#selAddUserLevel").removeClass('is-invalid');
                $("#selAddUserLevel").attr('title', '');
                $("#txtAddUserName").focus();
                $("#selAddUserLevel").select2('val', '0');
            });


            //============================== EDIT USER ==============================
            // actionEditUser is generated by datatables and open the modalEditUser(modal) to collect the id of the specified rows
            $(document).on('click', '.actionEditUser', function(){
                // the user-id(attr) is inside the datatables of UserController that will be use to collect the user-id
                let userId = $(this).attr('user-id'); 
                
                // after clicking the actionEditUser(button) the userId will be pass to the txtEditUserId(input=hidden) and when the form is submitted this will be pass to ajax and collect user-id that will be use to query the user-id in the UserController to update the user
                $("#txtEditUserId").val(userId);
                
                // COLLECT THE userId AND PASS TO INPUTS, BASED ON THE CLICKED ROWS
                // GetUserByIdToEdit() function is inside User.js and pass the userId as an argument when passing the ajax that will be use to query the user-id of get_user_by_id() method inside UserController and pass the fetched user based on that query as $user(variable) to pass the values in the inputs of modalEditUser and also to validate the fetched values, inside GetUserByIdToEdit under User.js
                GetUserByIdToEdit(userId);
                
                // VALIDATION(errors)
                $("#txtEditUserName").removeClass('is-invalid');
                $("#txtEditUserName").attr('title', '');
                $("#txtEditUserUserName").removeClass('is-invalid');
                $("#txtEditUserUserName").attr('title', '');
                $("#txtEditUserEmail").removeClass('is-invalid');
                $("#txtEditUserEmail").attr('title', '');
                $("#txtEditUserEmpId").removeClass('is-invalid');
                $("#txtEditUserEmpId").attr('title', '');
                $("#selEditUserLevel").removeClass('is-invalid');
                $("#selEditUserLevel").attr('title', '');
                $("#txtEditUserName").focus();
                // $("#selEditUserLevel").select2('val', '0');
                $("#chkEditUserWithEmail").prop('checked', 'checked');
            });

            // The EditUser(); function is inside public/js/my_js/User.js
            // after the submission, the ajax request will pass the formEditUser(form) of its data(input) in the uri(edit_user)
            // then the controller will handle that uri to use specific method called edit_user() inside UserController
            $("#formEditUser").submit(function(event){
                event.preventDefault();
                EditUser();
            });


            //============================== CHANGE USER STATUS ==============================
            // aChangeUserStat is generated by datatables and open the modalChangeUserStat(modal) to collect and change the id & status of the specified rows
            $(document).on('click', '.actionChangeUserStat', function(){
                let userStat = $(this).attr('status'); // the status will collect the value (1-active, 2-inactive)
                let userId = $(this).attr('user-id'); // the user-id(attr) is inside the datatables of UserController that will be use to collect the user-id

                $("#txtChangeUserStatUserStat").val(userStat); // collect the user status id the default is 2, this will be use to change the user status when the formChangeUserStat(form) is submitted
                $("#txtChangeUserStatUserId").val(userId); // after clicking the aChangeUserStat(button) the userId will be pass to the txtChangeUserStatUserId(input=hidden) and when the form is submitted this will be pass to ajax and collect user-id that will be use to query the user-id in the UserController to update the status of the user

                if(userStat == 1){
                    $("#lblChangeUserStatLabel").text('Are you sure to activate?'); 
                    $("#h4ChangeUserTitle").html('<i class="fa fa-user"></i> Activate User');
                }
                else{
                    $("#lblChangeUserStatLabel").text('Are you sure to deactivate?');
                    $("#h4ChangeUserTitle").html('<i class="fa fa-user"></i> Deactivate User');
                }
            });

            // The ChangeUserStatus(); function is inside public/js/my_js/User.js
            // after the submission, the ajax request will pass the formChangeUserStat(form) of data(input) in the uri(change_user_stat)
            // then the controller will handle that uri to use specific method called change_user_stat() inside UserController
            $("#formChangeUserStat").submit(function(event){
                event.preventDefault();
                ChangeUserStatus();
            });


            //============================== RESET USER PASSWORD ==============================
            // aResetUserPass is generated by datatables to collect the id of the specified rows
            $(document).on('click', '.actionResetUserPassword', function(){
                let userId = $(this).attr('user-id');
                $("#txtResetUserPasswordUserId").val(userId);
            });

            // The ResetUserPass(); function is inside public/js/my_js/User.js
            // after the submission, the ajax request will pass the formResetUserPassword(form) of data(input) in the uri(reset_password)
            // then the controller will handle that uri to use specific method called reset_password() inside UserController
            $("#formResetUserPassword").submit(function(event){
                event.preventDefault();
                ResetUserPass();
            });


            //============================== SIGN OUT ==============================
            $(document).ready(function(){
                $("#formSignOut").submit(function(event){
                    event.preventDefault();
                    SignOut();
                });
            });


            $("#formImportUser").submit(function(event){
                event.preventDefault();
                $.ajax({
                    url: 'import_user',
                    method: 'post',
                    data: new FormData(this),
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function(){
                        // alert('Loading...');
                    },
                    success: function(JsonObject){
                        if(JsonObject['result'] == 1){
                        toastr.success('Importing Success!');
                        dataTableUsers.draw();
                        $("#modalImportUser").modal('hide');
                        }
                        else{
                        toastr.error('Importing Failed!');
                        }
                    },
                    error: function(data, xhr, status){
                        console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                    }
                });
            });

            


            // $(document).on('click', '.chkUser', function(){
            //     let userId = $(this).attr('user-id'); //2

            //     if($(this).prop('checked')){
            //         // Checked
            //         if(!arrSelectedUsers.includes(userId)){
            //             arrSelectedUsers.push(userId);
            //         }
            //     }
            //     else{  
            //         // Unchecked
            //         let index = arrSelectedUsers.indexOf(userId);
            //         arrSelectedUsers.splice(index, 1);
            //     }
            //     $("#lblNoOfPrintBatchSelUser").text(arrSelectedUsers.length);
            //     if(arrSelectedUsers.length <= 0){
            //         $("#btnShowModalPrintBatchUser").prop('disabled', 'disabled');
            //         $("#btnSendTUVBatchEmail").prop('disabled', 'disabled');

            //     }
            //     else{
            //         $("#btnShowModalPrintBatchUser").removeAttr('disabled');
            //         $("#btnSendTUVBatchEmail").removeAttr('disabled');

            //     }
            // });

            // $("#btnShowModalPrintBatchUser").click(function(){
            //     PrintBatchUser(arrSelectedUsers);
            //     // console.log(arrSelectedUsers);
            // });

            // $("#chkAddUserWithEmail").click(function(){
            //     if($(this).prop('checked')) {
            //         $("#txtAddUserEmail").removeAttr('disabled');
            //         $("#chkAddUserSendEmail").removeAttr('disabled');
            //         $("#chkAddUserSendEmail").prop('checked', 'checked');
            //     }
            //     else{
            //         $("#txtAddUserEmail").prop('disabled', 'disabled');
            //         $("#txtAddUserEmail").val('');
            //         $("#chkAddUserSendEmail").prop('disabled', 'disabled');
            //         $("#chkAddUserSendEmail").removeAttr('checked');
            //     }
            // });

            // $("#chkAddUserWithOQCStamp").click(function(){
            //     if($(this).prop('checked')) {
            //         $("#txtAddUserOQCStamp").removeAttr('disabled');
            //     }
            //     else{
            //         $("#txtAddUserOQCStamp").prop('disabled', 'disabled');
            //         $("#txtAddUserOQCStamp").val('');
            //     }
            // });

            // // Edit User
            // $("#btnEditUserGenBarcode").click(function(){
            //     let qrcode = $("#txtEditUserEmpId").val();
            //     GenerateUserQRCode(qrcode, 2, $("#txtEditUserId").val()); // For Edit
            // });

            // $("#chkEditUserWithOQCStamp").click(function(){
            //     if($(this).prop('checked')) {
            //         $("#txtEditUserOQCStamp").removeAttr('disabled');
            //     }
            //     else{
            //         $("#txtEditUserOQCStamp").prop('disabled', 'disabled');
            //     }
            // });

            // $("#chkEditUserWithEmail").click(function(){
            //     if($(this).prop('checked')) {
            //         $("#txtEditUserEmail").removeAttr('disabled');
            //         // $("#chkEditUserSendEmail").removeAttr('disabled');
            //         // $("#chkEditUserSendEmail").prop('checked', 'checked');
            //         $("#txtEditUserEmail").val($("#txtEditUserCurrEmail").val());
            //     }
            //     else{
            //         $("#txtEditUserEmail").prop('disabled', 'disabled');
            //         $("#txtEditUserEmail").val('');
            //         // $("#chkEditUserSendEmail").prop('disabled', 'disabled');
            //         // $("#chkEditUserSendEmail").removeAttr('checked');
            //     }
            // });

            // $("#chkSelAllUsers").click(function(){
            //     if($(this).prop('checked')) {
            //         $(".chkUser").prop('checked', 'checked');
            //         $("#btnShowModalPrintBatchUser").removeAttr('disabled');
            //         $("#lblNoOfPrintBatchSelUser").text('All');
            //         arrSelectedUsers = 0;
            //     }
            //     else{
            //         // $(".chkUser").removeAttr('checked');
            //         dataTableUsers.draw();
            //         arrSelectedUsers = [];
            //         $("#btnShowModalPrintBatchUser").prop('disabled', 'disabled');
            //         $("#lblNoOfPrintBatchSelUser").text('0');
            //     }
            // });

        }); // JQUERY DOCUMENT READY END
    </script>
    
    @endsection
@endauth