//============================== ADD WORKLOAD ==============================
function AddWorkload(){
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

    let formData = new FormData($('#formAddWorkload')[0]);

	$.ajax({
        url: "add_workload",
        method: "post",
        processData: false,
        contentType: false,
        data: formData,
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddWorkloadIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddWorkload").prop('disabled', 'disabled');
        },
        success: function(response){
            if(response['validation'] == 'hasError'){
                toastr.error('Saving workload failed!');
                if(response['error']['name'] === undefined){
                    $("#txtAddWorkloadName").removeClass('is-invalid');
                    $("#txtAddWorkloadName").attr('title', '');
                }
                else{
                    $("#txtAddWorkloadName").addClass('is-invalid');
                    $("#txtAddWorkloadName").attr('title', response['error']['name']);
                }

                if(response['error']['work_instruction_title'] === undefined){
                    $("#txtAddWorkloadWorkInstructionTitle").removeClass('is-invalid');
                    $("#txtAddWorkloadWorkInstructionTitle").attr('title', '');
                }
                else{
                    $("#txtAddWorkloadWorkInstructionTitle").addClass('is-invalid');
                    $("#txtAddWorkloadWorkInstructionTitle").attr('title', response['error']['work_instruction_title']);
                }

                if(response['error']['description'] === undefined){
                    $("#txtAddWorkloadDescription").removeClass('is-invalid');
                    $("#txtAddWorkloadDescription").attr('title', '');
                }
                else{
                    $("#txtAddWorkloadDescription").addClass('is-invalid');
                    $("#txtAddWorkloadDescription").attr('title', response['error']['description']);
                }
            }else if(response['result'] == 1){
                $("#modalAddWorkload").modal('hide');
                $("#formAddWorkload")[0].reset();
                toastr.success('Workload was succesfully saved!');
                dataTableWorkloads.draw(); // reload the tables after insertion
            }else if(response['result'] == 0){
                $("#modalAddWorkload").modal('hide');
                $("#formAddWorkload")[0].reset();
                toastr.success("Workload was succesfully saved!" + ' ' + "<span class='text-warning'>Note that there is no file was uploaded</span>");
                dataTableWorkloads.draw(); // reload the tables after insertion
            }

            $("#iBtnAddWorkloadIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddWorkload").removeAttr('disabled');
            $("#iBtnAddWorkloadIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            console.log(data);
            console.log(xhr);
            console.log(status);
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddWorkloadIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddWorkload").removeAttr('disabled');
            $("#iBtnAddWorkloadIcon").addClass('fa fa-check');
        }
    });
}


//============================== EDIT WORKLOAD BY ID TO EDIT ==============================
function GetWorkloadByIdToEdit(workloadId){
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
        url: "get_workload_by_id",
        method: "get",
        data: {
            workload_id: workloadId
        },
        dataType: "json",
        beforeSend: function(){

        },
        success: function(response){
            let workload = response['workload'];
            if(workload.length > 0){
                $("#txtEditWorkloadName").val(workload[0].name);
                $("#txtEditWorkloadWorkInstructionTitle").val(workload[0].work_instruction_title);
                $("#txtEditWorkloadDescription").val(workload[0].description);
                $("#txtEditWorkloadFile").val(workload[0].file);

                $('#chkEditFile').on('click', function(){
                    $("#txtEditWorkloadName").val(workload[0].name);
                    $("#txtEditWorkloadWorkInstructionTitle").val(workload[0].work_instruction_title);
                    $("#txtEditWorkloadDescription").val(workload[0].description);
                    $("#txtEditWorkloadFile").val(workload[0].file);
    
                    // add checked attribute when #chkEditFile is clicked
                    $('#chkEditFile').attr('checked', 'checked');

                    // check if #chkEditFile is checked, if it is checked then hide #txtEditWorkloadFile and show the #fileEditWorkloadFile and hide the #btnEditWorkload
                    if($('#chkEditFile').is(':checked')){
                        $('#txtEditWorkloadFile').addClass('d-none'); // hide the #txtEditWorkloadFile
                        $('#fileEditWorkloadFile').removeClass('d-none'); // show #fileEditWorkloadFile
                        $('#btnEditWorkload').removeClass('d-none'); // show the #btnEditWorkload(button)

                        // set disabled to false on all input fields to be able to edit the input fields
                        $("#txtEditWorkloadName").prop('disabled', false);
                        $("#txtEditWorkloadWorkInstructionTitle").prop('disabled', false);
                        $("#txtEditWorkloadDescription").prop('disabled', false);
                        $("#txtEditWorkloadFile").prop('disabled', false);
                    }
                    // if checked attr is remove(uncheck) on the #chkEditFile(checkbox) then execute the code
                    else{
                        $('#chkEditFile').removeAttr('checked'); // remove the checked on #chkEditFile(checkbox)
                        $('#fileEditWorkloadFile').addClass('d-none');// hide #fileEditWorkloadFile
                        $('#fileEditWorkloadFile').val(''); // reset the value of #fileEditWorkloadFile(input type file)
                        $('#txtEditWorkloadFile').removeClass('d-none'); // show the #txtEditWorkloadFile
                        $('#btnEditWorkload').addClass('d-none'); // hide the #btnEditWorkload(button)

                        // add disabled on all input fields to restrict the user for editing
                        $("#txtEditWorkloadName").prop('disabled', true);
                        $("#txtEditWorkloadWorkInstructionTitle").prop('disabled', true);
                        $("#txtEditWorkloadDescription").prop('disabled', true);
                        $("#txtEditWorkloadFile").prop('disabled', true);

                        // remove the error border & title
                        $("#txtEditWorkloadName").removeClass('is-invalid');
                        $("#txtEditWorkloadName").attr('title', '');
                        $("#txtEditWorkloadWorkInstructionTitle").removeClass('is-invalid');
                        $("#txtEditWorkloadWorkInstructionTitle").attr('title', '');
                        $("#txtEditWorkloadDescription").removeClass('is-invalid');
                        $("#txtEditWorkloadDescription").attr('title', '');
                    }
                });
            
            }
            else{
                toastr.warning('No Workload record found!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        },
        contentType: false
    });
}


//============================== EDIT WORKLOAD ==============================
function EditWorkload(){
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

    let formData = new FormData($('#formEditWorkload')[0]);

    $.ajax({
        url: "edit_workload",
        method: "post",
        processData: false,
        contentType: false,
        data: formData,
        dataType: "json",
        beforeSend: function(){
            $("#iBtnEditWorkloadIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnEditWorkload").prop('disabled', 'disabled');
        },
        success: function(response){
            if(response['validation'] == 'hasError'){
                toastr.error('Updating workload failed!');

                if(response['error']['name'] === undefined){
                    $("#txtEditWorkloadName").removeClass('is-invalid');
                    $("#txtEditWorkloadName").attr('title', '');
                }
                else{
                    $("#txtEditWorkloadName").addClass('is-invalid');
                    $("#txtEditWorkloadName").attr('title', response['error']['name']);
                }

                if(response['error']['work_instruction_title'] === undefined){
                    $("#txtEditWorkloadWorkInstructionTitle").removeClass('is-invalid');
                    $("#txtEditWorkloadWorkInstructionTitle").attr('title', '');
                }
                else{
                    $("#txtEditWorkloadWorkInstructionTitle").addClass('is-invalid');
                    $("#txtEditWorkloadWorkInstructionTitle").attr('title', response['error']['work_instruction_title']);
                }

                if(response['error']['description'] === undefined){
                    $("#txtEditWorkloadDescription").removeClass('is-invalid');
                    $("#txtEditWorkloadDescription").attr('title', '');
                }
                else{
                    $("#txtEditWorkloadDescription").addClass('is-invalid');
                    $("#txtEditWorkloadDescription").attr('title', response['error']['description']);
                }
            }else if(response['result'] == 1){
                toastr.success('Workload was succesfully updated!');
                $("#modalEditWorkload").modal('hide'); // hide modal
                dataTableWorkloads.draw(); // reload the datatables after the update operation

                // reset the formEditWorkload values including checkbox, showing/hiding input file/text, disabled fields and remove error border
                resetModalValue();
                $("#formEditWorkload")[0].reset();
                $('#chkEditFile').removeAttr('checked'); // uncheck the Edit Attachment
                
                // check if the #chkEditFile(checkbox) has no attribute checked then hide then fileEditWorkloadFile(input type file) and show txtEditWorkloadFile(input type text)
                var chkEditFile = $('#chkEditFile').attr('checked');
                if (typeof chkEditFile === 'undefined' || chkEditFile === false) {
                    $('#fileEditWorkloadFile').addClass('d-none')
                    $('#txtEditWorkloadFile').removeClass('d-none');
                }
            }else if(response['result'] == 0){
                toastr.success("Workload was succesfully updated!" + ' ' + "<span class='text-warning'>Note that there is no file was uploaded</span>");
                $("#modalEditWorkload").modal('hide'); // hide modal
                dataTableWorkloads.draw(); // reload the datatables after the update operation
                
                // reset the formEditWorkload values including checkbox, showing/hiding input file/text, disabled fields and remove error border
                resetModalValue(); 
                $("#formEditWorkload")[0].reset();
                $('#chkEditFile').removeAttr('checked'); // uncheck the Edit Attachment
                
                // check if the #chkEditFile(checkbox) has no attribute checked then hide then fileEditWorkloadFile(input type file) and show txtEditWorkloadFile(input type text)
                var chkEditFile = $('#chkEditFile').attr('checked');
                if (typeof chkEditFile === 'undefined' || chkEditFile === false) {
                    $('#fileEditWorkloadFile').addClass('d-none');
                    $('#txtEditWorkloadFile').removeClass('d-none');
                }
            }
            $("#iBtnEditWorkloadIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditWorkload").removeAttr('disabled');
            $("#iBtnEditWorkloadIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnEditWorkloadIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditWorkload").removeAttr('disabled');
            $("#iBtnEditWorkloadIcon").addClass('fa fa-check');
        }
    });
}
//============================== RESET EDIT MODAL VALUES ==============================
function resetModalValue(){
        $('#chkEditFile').prop('checked', ''); // uncheck the checkbox
        $('#fileEditWorkloadFile').addClass('d-none'); // hide the file type
        $('#fileEditWorkloadFile').val(''); // reset the file type value
        $('#txtEditWorkloadFile').removeClass('d-none'); // show the text type(for viewing the value)
        $('#btnEditWorkload').addClass('d-none'); // hide the edit button(for edit)

        // disabled the input fields
        $("#txtEditWorkloadName").prop('disabled', true);
        $("#txtEditWorkloadWorkInstructionTitle").prop('disabled', true);
        $("#txtEditWorkloadDescription").prop('disabled', true);
        $("#txtEditWorkloadFile").prop('disabled', true);

        // remove the error border & title
        $("#txtEditWorkloadName").removeClass('is-invalid');
        $("#txtEditWorkloadName").attr('title', '');
        $("#txtEditWorkloadWorkInstructionTitle").removeClass('is-invalid');
        $("#txtEditWorkloadWorkInstructionTitle").attr('title', '');
        $("#txtEditWorkloadDescription").removeClass('is-invalid');
        $("#txtEditWorkloadDescription").attr('title', '');
}


//============================== DELETE WORKLOAD ==============================
function DeleteWorkload(){
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
        url: "delete_workload",
        method: "post",
        data: $('#formDeleteWorkload').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnDeleteWorkloadIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnDeleteWorkload").prop('disabled', 'disabled');
        },
        success: function(response){
            let result = response['result'];
            if(result == 1){
                $("#modalDeleteWorkload").modal('hide');
                $("#formDeleteWorkload")[0].reset();
                toastr.success('Workload successfully deleted');
            }
            else{
                toastr.warning('No workload found!');
            }
            
            $("#iBtnDeleteWorkloadIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnDeleteWorkload").removeAttr('disabled');
            $("#iBtnDeleteWorkloadIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnDeleteWorkloadIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnDeleteWorkload").removeAttr('disabled');
            $("#iBtnDeleteWorkloadIcon").addClass('fa fa-check');
        }
    });
}


//============================== RESTORE WORKLOAD ==============================
function RestoreWorkload(){
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
        url: "restore_workload",
        method: "post",
        data: $('#formRestoreWorkload').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnRestoreWorkloadIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnRestoreWorkload").prop('disabled', 'disabled');
        },
        success: function(response){
            let result = response['result'];
            if(result == 1){
                $("#modalRestoreWorkload").modal('hide');
                $("#formRestoreWorkload")[0].reset();
                toastr.success('Workload successfully restored');
            }
            else{
                toastr.warning('Cannot restore the workload');
            }
            
            $("#iBtnRestoreWorkloadIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnRestoreWorkload").removeAttr('disabled');
            $("#iBtnRestoreWorkloadIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnRestoreWorkloadIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnRestoreWorkload").removeAttr('disabled');
            $("#iBtnRestoreWorkloadIcon").addClass('fa fa-check');
        }
    });
}