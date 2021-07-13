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
        success: function(JsonObject){
            let user = JsonObject['user'];
            let qrcode = JsonObject['qrcode'];
            if(user.length > 0){
                $("#txtEditUserName").val(user[0].name);
                $("#txtEditUserUserName").val(user[0].username);
                $("#txtEditUserEmail").val(user[0].email);
                $("#txtEditUserCurrEmail").val(user[0].email);
                $("#txtEditUserEmpId").val(user[0].employee_id);
                $("#selEditUserLevel").val(user[0].user_level_id).trigger('change');
                $("#selEditUserPosition").val(user[0].position).trigger('change');
                // $("#selEditUserLevel").select2('val', user[0].user_level_id);

                if(user[0].email == null || user[0].email == ''){
                    $("#chkEditUserWithEmail").removeAttr('checked');
                    $("#txtEditUserEmail").prop('disabled', 'disabled');
                    // $("#chkEditUserSendEmail").removeAttr('checked');
                    // $("#chkEditUserSendEmail").prop('disabled', 'disabled');
                }
                else{
                    $("#chkEditUserWithEmail").prop('checked', 'checked');
                    $("#txtEditUserEmail").removeAttr('disabled');
                    // $("#chkEditUserSendEmail").prop('checked', 'checked');
                    // $("#chkEditUserSendEmail").removeAttr('disabled');
                }

                if(user[0].oqc_stamps.length <= 0){
                    $("#chkEditUserWithOQCStamp").removeAttr('checked');
                    $("#txtEditUserOQCStamp").prop('disabled', 'disabled');
                    $("#txtEditUserOQCStamp").val('');
                }
                else{
                    $("#chkEditUserWithOQCStamp").prop('checked', 'checked');
                    $("#txtEditUserOQCStamp").removeAttr('disabled');
                    $("#txtEditUserOQCStamp").val(user[0].oqc_stamps[0].oqc_stamp);
                }

                $("#lblEditUserQRCodeVal").text(user[0].employee_id);
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
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalEditUser").modal('hide');
                $("#formEditUser")[0].reset();
                $("#selEditUserLevel").select2('val', '0');
                $("#txtEditUserEmail").removeAttr('disabled');
                // $("#chkEditUserSendEmail").removeAttr('disabled');
                // $("#chkEditUserSendEmail").prop('checked', 'checked');
                $("#chkEditUserWithEmail").prop('checked', 'checked');

                dataTableUsers.draw();
                toastr.success('User was succesfully saved!');

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
                }
            }
            else{
                toastr.error('Updating User Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtEditUserName").removeClass('is-invalid');
                    $("#txtEditUserName").attr('title', '');
                }
                else{
                    $("#txtEditUserName").addClass('is-invalid');
                    $("#txtEditUserName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['username'] === undefined){
                    $("#txtEditUserUserName").removeClass('is-invalid');
                    $("#txtEditUserUserName").attr('title', '');
                }
                else{
                    $("#txtEditUserUserName").addClass('is-invalid');
                    $("#txtEditUserUserName").attr('title', JsonObject['error']['username']);
                }

                if(JsonObject['error']['employee_id'] === undefined){
                    $("#txtEditUserEmpId").removeClass('is-invalid');
                    $("#txtEditUserEmpId").attr('title', '');
                }
                else{
                    $("#txtEditUserEmpId").addClass('is-invalid');
                    $("#txtEditUserEmpId").attr('title', JsonObject['error']['employee_id']);
                }

                if(JsonObject['error']['user_level_id'] === undefined){
                    $("#selEditUserLevel").removeClass('is-invalid');
                    $("#selEditUserLevel").attr('title', '');
                }
                else{
                    $("#selEditUserLevel").addClass('is-invalid');
                    $("#selEditUserLevel").attr('title', JsonObject['error']['user_level_id']);
                }

                if(JsonObject['error']['email'] === undefined){
                    $("#txtEditUserEmail").removeClass('is-invalid');
                    $("#txtEditUserEmail").attr('title', '');
                }
                else{
                    $("#txtEditUserEmail").addClass('is-invalid');
                    $("#txtEditUserEmail").attr('title', JsonObject['error']['email']);
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
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalChangeUserStat").modal('hide');
                $("#formChangeUserStat")[0].reset();

                dataTableUsers.draw();

                if($("#txtChangeUserStatUserStat").val() == 1){
                    toastr.success('User Activation Success!');
                }
                else{
                    toastr.success('User Deactivation Success!');
                }
            }
            else{
                if($("#txtChangeUserStatUserStat").val() == 1){
                    toastr.error('User Activation Failed!');
                }
                else{
                    toastr.error('User Deactivation Failed!');
                }
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

// Generate User QR Code
// function GenerateUserQRCode(qrcode, action, userId){
//     toastr.options = {
//         "closeButton": false,
//         "debug": false,
//         "newestOnTop": true,
//         "progressBar": true,
//         "positionClass": "toast-top-right",
//         "preventDuplicates": false,
//         "onclick": null,
//         "showDuration": "300",
//         "hideDuration": "3000",
//         "timeOut": "3000",
//         "extendedTimeOut": "3000",
//         "showEasing": "swing",
//         "hideEasing": "linear",
//         "showMethod": "fadeIn",
//         "hideMethod": "fadeOut",
//     };

//     $.ajax({
//         url: "generate_user_qrcode",
//         method: "get",
//         data: {
//             qrcode: qrcode,
//             action: action,
//             user_id: userId,
//         },
//         dataType: "json",
//         beforeSend: function(){
            
//         },
//         success: function(JsonObject){
//             if(action == 1){
//             if(JsonObject['result'] == '1'){
//                 $("#imgAddUserBarcode").attr("src", JsonObject['qrcode']);
//                 $("#lblAddUserQRCodeVal").text(qrcode);
//             }
//             else if(JsonObject['result'] == '0'){
//                 toastr.error('Generating QR Code Failed!');
//                 $("#imgAddUserBarcode").attr("src", JsonObject['qrcode']);
//                 $("#lblAddUserQRCodeVal").text('0');
//             }
//             else if(JsonObject['result'] == '2'){
//                 toastr.warning('Cannot Generate Duplicate Employee ID!');
//                 $("#imgAddUserBarcode").attr("src", JsonObject['qrcode']);
//                 $("#lblAddUserQRCodeVal").text('0');
//             }
//             }
//             else if(action == 2){
//             if(JsonObject['result'] == '1'){
//                 $("#imgEditUserBarcode").attr("src", JsonObject['qrcode']);
//                 $("#lblEditUserQRCodeVal").text(qrcode);
//             }
//             else if(JsonObject['result'] == '0'){
//                 toastr.error('Generating QR Code Failed!');
//             }
//             else if(JsonObject['result'] == '2'){
//                 toastr.warning('Cannot Generate Duplicate Employee ID!');
//             }
//             }
//         },
//         error: function(data, xhr, status){
//             alert('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//         }
//     });
// }

// function PrintWHMatIssu(){
//   popup = window.open();
//   let content = '';
//   content += '<html>';
//   content += '<head>';
//     content += '<title></title>';
//     content += '<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">';
//     content += '<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>';
//     content += '<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>';
//     content += '<style type="text/css">';
//       content += '.divBorder{';
//         content += 'border: 2px solid black;';
//               content += 'min-width: 225px;';
//               content += 'margin-top: 10px;';
//       content += '}';
//     content += '</style>';
//   content += '</head>';
//   content += '<body>';
//     content += '<div class="container-fluid">';
//       content += '<div class="row">';

//           // content += '<div class="col-sm-4">';
//             content += '<div class="divBorder">';
//               // content += '<center>';
//                 content += '<table>';
//                   content += '<tr>';
//                     content += '<td>';
//                       // content += '<center>';
//                         content += '<img src="' + imgResultMatIssueQrCode + '" style="max-width: 120px;">';
//                       // content += '</center>';
//                     content += '</td>';
//                     content += '<td>';
//                       content += '<label style="text-align: left; font-weight: bold; font-family: Arial; font-size: 18px;">' + lblGenWHMatIssuPoNo + '</label>';
//                       content += '<br>';
//                       content += '<label style="text-align: left; font-family: Arial Narrow; font-size: 18px;">' + lblGenWHMatIssuPoDevName + '</label><br>';
//                       content += '<label style="text-align: left; font-family: Arial Narrow; font-size: 18px;">' + lblGenWHMatIssuPoKitNo + " - " + lblGenWHMatIssuPoKitQty + '</label><br>';
//                     content += '</td>';
//                   content += '</tr>';
//                 content += '</table>';
//               // content += '</center>';
//             content += '</div>';
//           // content += '</div>';

//       content += '</div>';
//     content += '</div>';
//   content += '</body>';
//   content += '</html>';
//   popup.document.write(content);
//   // popup.focus(); //required for IE
//   // popup.print();
//   // popup.close();
// }

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