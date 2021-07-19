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
                dataTableUsers.draw(); // reload the tables after insertion
            }else if(response['result'] == 0){
                $("#modalAddWorkload").modal('hide');
                $("#formAddWorkload")[0].reset();
                toastr.success("Workload was succesfully saved!" + ' ' + "<span class='text-warning'>Note that you didn't upload a file</span>");
                dataTableUsers.draw(); // reload the tables after insertion
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