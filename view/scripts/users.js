var toggleAddUserModal = function(){
    $('#add-user-modal').modal('toggle');
}

var toggleEditUserModal = function(){
    $('#edit-user-modal').modal('toggle');
}

var toggleAddPlanToUserModal = function(){
    $('#add-plan-to-user-modal').modal('toggle');
}

var refreshUserList = function(){
    $.ajax({
        url: '/admin/users/get-users',
        method: 'post',
        data: {},
        success: function(responseJSON){ 
            var tableBodyHtml = '<tr><th>ID</th><th>First name</th><th>Last name</th><th>Email address</th><th>Plans</th><th>Actions</th></tr>';
            response = $.parseJSON(responseJSON);
            if (typeof response !== null) {
                if (response.result === true) {
                    if (Array.isArray(response.users) && response.users.length > 0) {
                        $.each(response.users, function(index, value){
                            tableBodyHtml += '<tr><td>' + value.id + '</td><td>' + value.first_name + '</td><td>' + value.last_name + '</td><td>' + value.email + '</td><td>' + value.plan_number + '</td><td><div class="btn-group"><button type="button" class="btn btn-info" onClick="editUser(\''+value.code+'\')">Edit</button><button type="button" class="btn btn-success" onClick="addPlanToUser(\''+value.code+'\')">Plans</button><button type="button" class="btn btn-danger" onClick="deleteUser(\''+value.code+'\')">Delete</button></div></td></tr>';
                        });
                    }else{
                        tableBodyHtml += '<tr><td colspan="6">No user found in the database</td></tr>';
                    }
                }else{
                    tableBodyHtml += '<tr><td colspan="6">No user found in the database</td></tr>';
                }
                $('#user-list tbody').html(tableBodyHtml);  
            }else{
                addErrorMessage('Error!', 'Please refresh your browser!');
            }
        },
        error: function(response){
            addErrorMessage('Error!', 'Please refresh your browser!');
        }
    });    
}

var editUser = function(code){
    $.ajax({
        url: '/admin/users/get-user',
        method: 'post',
        data: {code: code},
        success: function(responseJSON){ 
            response = $.parseJSON(responseJSON);
            if (typeof response !== null) {
                if (response.result === true) {
                    $('#edit-user-form input[name="code"]').val(response.user.code);
                    $('#edit-user-form input[name="firstName"]').val(response.user.first_name);
                    $('#edit-user-form input[name="lastName"]').val(response.user.last_name);
                    $('#edit-user-form input[name="email"]').val(response.user.email);
                    toggleEditUserModal();
                }else{
                    addErrorMessage('Error!', response.message);
                }
            }else{
                addErrorMessage('Error!', 'Please refresh your browser!');
            }
        },
        error: function(response){
            addErrorMessage('Error!', 'Please refresh your browser!');
        }
    });
    
}

var deleteUser = function(code){
    $('#delete-user-modal').modal('show');
    $("#yes-delete-user").on("click", function(){
        $.ajax({
            url: '/admin/users/delete',
            method: 'post',
            data: {code: code},
            success: function(responseJSON){ 
                response = $.parseJSON(responseJSON);
                if (typeof response !== null) {
                    if (response.result === true) {
                        $('#delete-user-modal').modal('hide');
                       //REFRESH USER LIST
                        refreshUserList();
                        //ADD SUCCESS MESSAGE
                        addSuccessMessage('User deleted!', 'You have just successfully deleted a user!');
                    }else{
                        addErrorMessage('Error!', response.message);
                    }
                }else{
                    addErrorMessage('Error!', 'Please refresh your browser!');
                }
            },
            error: function(response){
                addErrorMessage('Error!', 'Please refresh your browser!');
            }
        });
    });
}

var refreshUserPlanList = function(code){
    $.ajax({
        url: '/admin/users/get-plans',
        method: 'post',
        data: {code: code},
        success: function(responseJSON){ 
            response = $.parseJSON(responseJSON);
            if (typeof response !== null) {
                if (response.result === true) {
                    var planListBody = '<div class="row">';
                    var planSelectOptions = '';
                    if (Array.isArray(response.user_plans.selected) && response.user_plans.selected.length > 0) {
                        $.each(response.user_plans.selected, function(index, value){
                            planListBody += '<div class="col-sm-4"><div class="box box-info"><div class="box-header with-border"><h3 class="box-title">'+ value.plan_name +'</h3><div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" onClick="deletePlanFromUser(\''+value.up_code+'\', \''+code+'\');"><i class="fa fa-times"></i></button></div></div><div class="box-body">'+value.plan_description+'<br><a href="/admin/plans/'+value.code+'" target="_blank">more details...</a></div></div></div>';
                        });
                    }else{
                        planListBody += '<div class="col-sm-12"><div class="box box-danger"><div class="box-header with-border"><h3 class="box-title">The user has no plans added.</h3></div><div class="box-body">Add at least one plan so the user could start a new healthy life.</div></div></div>';
                    }
                    planListBody += '</div>';
                    $('#plan-container').html(planListBody);

                    if (Array.isArray(response.user_plans.not_selected) && response.user_plans.not_selected.length > 0) {
                        $.each(response.user_plans.not_selected, function(index, value){
                            planSelectOptions += '<option value="' + value.code + '">' + value.plan_name + '</option>';
                        });
                    }else{
                        planSelectOptions += '<option value="0">No more plans to add</option>';
                    }

                    $('#select-plan-to-user').html(planSelectOptions);
                    refreshUserList();
                }
            }else{
                addErrorMessage('Error!', 'Please refresh your browser!');
            }  
        },
        error: function(response){
            addErrorMessage('Error!', 'Please refresh your browser!');
        }
    });
}

var addPlanToUser = function(code){
    $('#add-plan-to-user-form input[name="user"]').val(code);
    refreshUserPlanList(code);
    toggleAddPlanToUserModal();
}

var deletePlanFromUser = function(code, userCode){
    $.ajax({
        url: '/admin/users/delete-plan',
        method: 'post',
        data: {code: code},
        success: function(responseJSON){ 
            response = $.parseJSON(responseJSON);
            if (typeof response !== null) {
                if (response.result === true) {
                    refreshUserPlanList(userCode);
                    addSuccessMessage('Plan removed!', 'You have just successfully removed a plan!');
                }else{
                    addErrorMessage('Error!', response.message);
                }
            }else{
                addErrorMessage('Error!', 'Please refresh your browser!');
            }    
        },
        error: function(response){
            addErrorMessage('Error!', 'Please refresh your browser!');
        }
    });
}

$('#add-user-form').submit(function(e){
    e.preventDefault();

    var myForm = document.getElementById('add-user-form');
    var form_data = new FormData(myForm);

    $.ajax({
        url: '/admin/users/add',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        beforeSend: function(){
            $('#add-user-button').html('<i class="fa fa-refresh fa-spin fa-fw"></i>').fadeIn();
        },
        complete: function(){
            $('#add-user-button').html('Add user').fadeIn();
        },
        success: function(responseJSON){ 
            response = $.parseJSON(responseJSON);
            if (typeof response !== null) {
                if (response.result === true) {
                    //REFRESH USER LIST
                    refreshUserList();
                    //CLEAR FORM
                    $('#add-user-form')[0].reset();
                    //HIDE MODAL
                    toggleAddUserModal();
                    //ADD SUCCESS MESSAGE
                    addSuccessMessage('New user added!', 'You have just successfully added a new user to the webapp!');
                }else{
                    addErrorMessage('Error!', response.message);
                }
            }else{
                addErrorMessage('Error!', 'Please refresh your browser!');
            }             
        },
        error: function(response){
            addErrorMessage('Error!', 'Please refresh your browser!');
        }
    });
});

//EDIT USER
$('#edit-user-form').submit(function(e){
    e.preventDefault();

    var myForm = document.getElementById('edit-user-form');
    var form_data = new FormData(myForm);

    $.ajax({
        url: '/admin/users/edit',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        beforeSend: function(){
            $('#edit-user-button').html('<i class="fa fa-refresh fa-spin fa-fw"></i>').fadeIn();
        },
        complete: function(){
            $('#edit-user-button').html('Edit user').fadeIn();
        },
        success: function(responseJSON){ 
            response = $.parseJSON(responseJSON);
            if (typeof response !== null) {
                if (response.result === true) {
                    //REFRESH USER LIST
                    refreshUserList();
                    //CLEAR FORM
                    $('#edit-user-form')[0].reset();
                    //HIDE MODAL
                    toggleEditUserModal();
                    //ADD SUCCESS MESSAGE
                    addSuccessMessage('User edited!', 'You have just successfully edited a user!');
                }else{
                    addErrorMessage('Error!', response.message);
                }
            }else{
                addErrorMessage('Error!', 'Please refresh your browser!');
            }             
        },
        error: function(response){
            addErrorMessage('Error!', 'Please refresh your browser!');
        }
    });
});

$('#add-plan-to-user-form').submit(function(e){
    e.preventDefault();
    var userCode = $('#add-plan-to-user-form input[name="user"]').val();
    var myForm = document.getElementById('add-plan-to-user-form');
    var formData = new FormData(myForm);

    $.ajax({
        url: '/admin/users/add-plan',
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        type: 'post',
        beforeSend: function(){
            $('#add-plan-to-user-button').html('<i class="fa fa-refresh fa-spin fa-fw"></i>').fadeIn();
        },
        complete: function(){
            $('#add-plan-to-user-button').html('Add user').fadeIn();
        },
        success: function(responseJSON){ 
            response = $.parseJSON(responseJSON);
            if (typeof response !== null) {
                if (response.result === true) {
                    //REFRESH USER LIST
                    refreshUserPlanList(userCode);
                    //ADD SUCCESS MESSAGE
                    addSuccessMessage('Plan added to user!', 'You have just successfully added a plant to the new user!');
                }else{
                    addErrorMessage('Error!', response.message);
                }
            }else{
                addErrorMessage('Error!', 'Please refresh your browser!');
            }             
        },
        error: function(response){
            addErrorMessage('Error!', 'Please refresh your browser!');
        }
    });
});



