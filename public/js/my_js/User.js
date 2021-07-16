//============================== ADD USER ==============================
function AddUser(){
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "3000",
        "timeOut": "3000",
        "extendedTimeOut": "3000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
    };

	$.ajax({
        url: "add_user",
        method: "post",
        data: $('#formAddUser').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddUserIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddUser").prop('disabled', 'disabled');
        },
        success: function(response){
            if(response['validation'] == 'hasError'){
                toastr.error('Saving User Failed!');
                if(response['error']['name'] === undefined){
                    $("#txtAddUserName").removeClass('is-invalid');
                    $("#txtAddUserName").attr('title', '');
                }
                else{
                    $("#txtAddUserName").addClass('is-invalid');
                    $("#txtAddUserName").attr('title', response['error']['name']);
                }

                if(response['error']['position'] === undefined){
                    $("#txtAddUserPosition").removeClass('is-invalid');
                    $("#txtAddUserPosition").attr('title', '');
                }
                else{
                    $("#txtAddUserPosition").addClass('is-invalid');
                    $("#txtAddUserPosition").attr('title', response['error']['username']);
                }

                if(response['error']['username'] === undefined){
                    $("#txtAddUserUserName").removeClass('is-invalid');
                    $("#txtAddUserUserName").attr('title', '');
                }
                else{
                    $("#txtAddUserUserName").addClass('is-invalid');
                    $("#txtAddUserUserName").attr('title', response['error']['username']);
                }

                if(response['error']['user_level_id'] === undefined){
                    $("#selAddUserLevel").removeClass('is-invalid');
                    $("#selAddUserLevel").attr('title', '');
                }
                else{
                    $("#selAddUserLevel").addClass('is-invalid');
                    $("#selAddUserLevel").attr('title', response['error']['user_level_id']);
                }
            }else if(response['result'] == 1){
                $("#modalAddUser").modal('hide');
                $("#formAddUser")[0].reset();
                $("#selAddUserLevel").select2('val', '0');
                toastr.success('User was succesfully saved!');
                dataTableUsers.draw(); // reload the tables after insertion
            }

            $("#iBtnAddUserIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddUser").removeAttr('disabled');
            $("#iBtnAddUserIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddUserIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddUser").removeAttr('disabled');
            $("#iBtnAddUserIcon").addClass('fa fa-check');
        }
    });
}


//============================== EDIT USER BY ID TO EDIT ==============================
function GetUserByIdToEdit(userId){
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "3000",
        "timeOut": "3000",
        "extendedTimeOut": "3000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
    };

    $.ajax({
        url: "get_user_by_id",
        method: "get",
        data: {
            user_id: userId
        },
        dataType: "json",
        beforeSend: function(){
            
        },
        success: function(response){
            let user = response['user'];
            if(user.length > 0){
                $("#txtEditUserName").val(user[0].name);
                $("#txtEditUserUserName").val(user[0].username);
                $("#txtEditUserPosition").val(user[0].position);
                $("#selEditUserLevel").val(user[0].user_level_id).trigger('change');
            }
            else{
                toastr.warning('No User Record Found!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}


//============================== EDIT USER ==============================
function EditUser(){
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "3000",
        "timeOut": "3000",
        "extendedTimeOut": "3000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
    };

    $.ajax({
        url: "edit_user",
        method: "post",
        data: $('#formEditUser').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnEditUserIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnEditUser").prop('disabled', 'disabled');
        },
        success: function(response){
            if(response['validation'] == 'hasError'){
                toastr.error('Updating User Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtEditUserName").removeClass('is-invalid');
                    $("#txtEditUserName").attr('title', '');
                }
                else{
                    $("#txtEditUserName").addClass('is-invalid');
                    $("#txtEditUserName").attr('title', response['error']['name']);
                }

                if(response['error']['position'] === undefined){
                    $("#txtEditPosition").removeClass('is-invalid');
                    $("#txtEditPosition").attr('title', '');
                }
                else{
                    $("#txtEditPosition").addClass('is-invalid');
                    $("#txtEditPosition").attr('title', response['error']['name']);
                }

                if(response['error']['username'] === undefined){
                    $("#txtEditUserUserName").removeClass('is-invalid');
                    $("#txtEditUserUserName").attr('title', '');
                }
                else{
                    $("#txtEditUserUserName").addClass('is-invalid');
                    $("#txtEditUserUserName").attr('title', response['error']['username']);
                }

                if(response['error']['user_level_id'] === undefined){
                    $("#selEditUserLevel").removeClass('is-invalid');
                    $("#selEditUserLevel").attr('title', '');
                }
                else{
                    $("#selEditUserLevel").addClass('is-invalid');
                    $("#selEditUserLevel").attr('title', response['error']['user_level_id']);
                }
            }else{
                if(response['result'] == 1){
                    $("#modalEditUser").modal('hide');
                    $("#formEditUser")[0].reset();
                    $("#selEditUserLevel").select2('val', '0');
    
                    dataTableUsers.draw();
                    toastr.success('User was succesfully saved!');
                }else{
                    toastr.warning(response['tryCatchError']);
                }
            }
            
            $("#iBtnEditUserIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditUser").removeAttr('disabled');
            $("#iBtnEditUserIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnEditUserIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditUser").removeAttr('disabled');
            $("#iBtnEditUserIcon").addClass('fa fa-check');
        }
    });
}


//============================== SIGN IN ==============================
function SignIn(){
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "3000",
        "timeOut": "3000",
        "extendedTimeOut": "3000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
    };

    $.ajax({
        url: "sign_in",
        method: "post",
        data: $('#formSignIn').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnSignInIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnSignIn").prop('disabled', 'disabled');
        },
        success: function(response){
            if(response['validation'] == 'hasError'){
                if(response['error']['username'] === undefined){
                    $("#txtSignInUsername").removeClass('is-invalid');
                    $("#txtSignInUsername").attr('title', '');
                }
                else{
                    $("#txtSignInUsername").addClass('is-invalid');
                    $("#txtSignInUsername").attr('title', response['error']['username']);
                    // toastr.error(response['error']['username']);
                }

                if(response['error']['password'] === undefined){
                    $("#txtSignInPassword").removeClass('is-invalid');
                    $("#txtSignInPassword").attr('title', '');
                }
                else{
                    $("#txtSignInPassword").addClass('is-invalid');
                    $("#txtSignInPassword").attr('title', response['error']['password']);
                    // toastr.error(response['error']['password']);
                }
            } 
            else{
                if(response['result'] == 0){
                    toastr.error(response['error_message']);
                    $("#txtSignInUsername").removeClass('is-invalid');
                    $("#txtSignInUsername").attr('title', '');
                    $("#txtSignInPassword").removeClass('is-invalid');
                    $("#txtSignInPassword").attr('title', '');
                }
                else {
                    if(response['status'] == 'inactive'){
                        toastr.error('Your account is inactive!');
                    }
                    else if(response['result'] == 1){
                        window.location = "user";
                    }
                    else if(response['result'] == 2){
                        window.location = "change_pass_view";
                    }
                }
            }
            $("#iBtnSignInIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnSignIn").removeAttr('disabled');
            $("#iBtnSignInIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnSignInIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnSignIn").removeAttr('disabled');
            $("#iBtnSignInIcon").addClass('fa fa-check');
        }
    });
}


//==============================SIGN OUT==============================
function SignOut(){
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "3000",
        "timeOut": "3000",
        "extendedTimeOut": "3000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
    };

    $.ajax({
        url: "sign_out",
        method: "post",
        data: $('#formSignOut').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnSignOutIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnSignOut").prop('disabled', 'disabled');
        },
        success: function(response){
            $("#iBtnSignOutIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnSignOut").removeAttr('disabled');
            $("#iBtnSignOutIcon").addClass('fa fa-check');
            if(response['result'] == 1){
                window.location = "/";
            }
            else{
                toastr.error('Logout Failed!');
            }
        },
        error: function(data, xhr, status){
            // toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnSignOutIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnSignOut").removeAttr('disabled');
            $("#iBtnSignOutIcon").addClass('fa fa-check');
        }
    });
}


//============================== LOGIN ANOTHER(change_password view) ==============================
function LoginAnother(){
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "3000",
        "timeOut": "3000",
        "extendedTimeOut": "3000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
    };

    $.ajax({
        url: "sign_out",
        method: "post",
        data: $('#formChangePassword').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnSignOutIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnSignOut").prop('disabled', 'disabled');
        },
        success: function(result){
            $("#iBtnSignOutIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnSignOut").removeAttr('disabled');
            $("#iBtnSignOutIcon").addClass('fa fa-check');
            if(result['result'] == 1){
                window.location = "/";
            }
            else{
                toastr.error('Logout Failed!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnSignOutIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnSignOut").removeAttr('disabled');
            $("#iBtnSignOutIcon").addClass('fa fa-check');
        }
    });
}


//============================== CHANGE PASSWORD ==============================
function ChangePassword(){
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "3000",
        "timeOut": "3000",
        "extendedTimeOut": "3000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
    };

    $.ajax({
        url: "change_pass",
        method: "post",
        data: $('#formChangePassword').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangePassIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangePass").prop('disabled', 'disabled');
        },
        success: function(response){
            if(response['validation'] == 'hasError'){
                if(response['error']['username'] === undefined){
                    $("#txtChangePasswordUsername").removeClass('is-invalid');
                    $("#txtChangePasswordUsername").attr('title', '');
                }
                else{
                    $("#txtChangePasswordUsername").addClass('is-invalid');
                    $("#txtChangePasswordUsername").attr('title', response['error']['username']);
                }

                if(response['error']['password'] === undefined){
                    $("#txtChangePasswordPassword").removeClass('is-invalid');
                    $("#txtChangePasswordPassword").attr('title', '');
                }
                else{
                    $("#txtChangePasswordPassword").addClass('is-invalid');
                    $("#txtChangePasswordPassword").attr('title', response['error']['password']);
                }

                if(response['error']['new_password'] === undefined){
                    $("#txtChangePasswordNewPassword").removeClass('is-invalid');
                    $("#txtChangePasswordNewPassword").attr('title', '');
                }
                else{
                    $("#txtChangePasswordNewPassword").addClass('is-invalid');
                    $("#txtChangePasswordNewPassword").attr('title', response['error']['new_password']);
                }

                if(response['error']['confirm_password'] === undefined){
                    $("#txtChangePasswordConfirmPassword").removeClass('is-invalid');
                    $("#txtChangePasswordConfirmPassword").attr('title', '');
                }
                else{
                    $("#txtChangePasswordConfirmPassword").addClass('is-invalid');
                    $("#txtChangePasswordConfirmPassword").attr('title', response['error']['confirm_password']);
                }
            }
            else{
                if(response['result'] == 1){
                    window.location = "user";
                }
                else{
                    toastr.error(response['error_message']);
                }
            }

            $("#iBtnChangePassIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangePass").removeAttr('disabled');
            $("#iBtnChangePassIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangePassIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangePass").removeAttr('disabled');
            $("#iBtnChangePassIcon").addClass('fa fa-check');
        }
    });
}


//============================== CHANGE USER STATUS ==============================
function ChangeUserStatus(){
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "3000",
        "timeOut": "3000",
        "extendedTimeOut": "3000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
    };

    $.ajax({
        url: "change_user_stat",
        method: "post",
        data: $('#formChangeUserStat').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangeUserStatIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangeUserStat").prop('disabled', 'disabled');
        },
        success: function(response){
            // ON GOING 07-14/2021 - JANNUS

            if(response['validation'] == 'hasError'){
                toastr.error('User activation failed!');
            }else{
                if(response['result'] == 1){
                    if($("#txtChangeUserStatUserStat").val() == 1){
                        toastr.success('User activation success!');
                        $("#txtChangeUserStatUserStat").val() == 2;
                    }
                    else{
                        toastr.success('User deactivation success!');
                        $("#txtChangeUserStatUserStat").val() == 1;
                    }
                }
                $("#modalChangeUserStat").modal('hide');
                $("#formChangeUserStat")[0].reset();
                dataTableUsers.draw();
            }


            $("#iBtnChangeUserStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeUserStat").removeAttr('disabled');
            $("#iBtnChangeUserStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangeUserStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeUserStat").removeAttr('disabled');
            $("#iBtnChangeUserStatIcon").addClass('fa fa-check');
        }
    });
}


//============================== RESET USER PASSWORD ==============================
function ResetUserPass(){
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "3000",
        "timeOut": "3000",
        "extendedTimeOut": "3000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
    };

    $.ajax({
        url: "reset_password",
        method: "post",
        data: $('#formResetUserPass').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnResetUserPassIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnResetUserPass").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                toastr.success('Reset Password Success!');
            }
            else{
                toastr.error('Resetting Password Failed!');
            }
            
            $("#modalResetUserPass").modal('hide');
            $("#iBtnResetUserPassIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnResetUserPass").removeAttr('disabled');
            $("#iBtnResetUserPassIcon").addClass('fa fa-check');

            if(JsonObject['has_email'] == 0){
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "showDuration": "0",
                    "hideDuration": "0",
                    "timeOut": "0",
                    "extendedTimeOut": "0",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut",
                    "tapToDismiss": false
                };

                // toastr.info("<center><b>USER INFO</b></center> " + "<b>Username: </b> " + JsonObject['user'][0]['username']  + "<br>" + "<b>Password: </b> " + JsonObject['password']);
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnResetUserPassIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnResetUserPass").removeAttr('disabled');
            $("#iBtnResetUserPassIcon").addClass('fa fa-check');
        }
    });
}


//============================== GET USER BY STATUS FOR DASHBOARD ==============================
function CountUserByStatForDashboard(status){
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "3000",
        "timeOut": "3000",
        "extendedTimeOut": "3000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
    };
    $.ajax({
        url: "get_user_by_stat",
        method: "get",
        data: {
            status: status
        },
        dataType: "json",
        beforeSend: function(){
            
        },
        success: function(JsonObject){
            if(JsonObject['user'].length > 0){
                $("#h3TotalNoOfUsers").text(JsonObject['user'].length);
            }
            else{
                toastr.warning('No User Record Found!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            return totalNoOfUsers;
        }
    });
}


//============================== GET USER LIST ==============================
function GetUserList(cboElement){
    let result = '<option value="">N/A</option>';
    $.ajax({
        url: 'get_user_list',
        method: 'get',
        dataType: 'json',
        beforeSend: function(){
            result = '<option value=""> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            if(JsonObject['users'].length > 0){
                result = '<option value="">N/A</option>';
                for(let index = 0; index < JsonObject['users'].length; index++){
                    let disabled = '';

                    if(JsonObject['users'][index].status == 2){
                        disabled = 'disabled';
                    }
                    else{
                        disabled = '';
                    }
                    result += '<option data-code="' + JsonObject['users'][index].employee_id + '" value="' + JsonObject['users'][index].id + '" ' + disabled + '>' + JsonObject['users'][index].name + '</option>';
                }
            }
            else{
                result = '<option value=""> -- No record found -- </option>';
            }

            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value=""> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}