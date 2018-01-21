var toggleAddPlanModal = function(){
    $('#add-plan-modal').modal('toggle');
}

var toggleEditPlanModal = function(){
    $('#edit-plan-modal').modal('toggle');
}

var toggleAddPlanDayModal = function(){
    $('#add-day-modal').modal('toggle');
}

var toggleEditDayModal = function(){
    $('#edit-day-modal').modal('toggle');
}

$('#edit-day-modal').on('hidden.bs.modal', function (e) {
  $('#add-exercise-to-day-form')[0].reset();
});

var refreshPlanList = function(){
    $.ajax({
        url: '/admin/plans/get-plans',
        method: 'post',
        data: {},
        success: function(responseJSON){ 
            var tableBodyHtml = '<tr><th>ID</th><th>Name</th><th>Description</th><th>Difficulty</th><th>Days</th><th>Actions</th></tr>';
            response = $.parseJSON(responseJSON);
            if (typeof response !== null) {
                if (response.result === true) {
                    if (Array.isArray(response.plans) && response.plans.length > 0) {
                        $.each(response.plans, function(index, value){
                            tableBodyHtml += '<tr><td>' + value.id + '</td><td>' + value.plan_name + '</td><td>' + value.plan_description + '</td><td>' + value.plan_difficulty_text + '</td><td>' + value.day_number + '</td><td><div class="btn-group"><button type="button" class="btn btn-info" onClick="editPlan(\''+value.code+'\')">Edit</button><a href="/admin/plans/'+value.code+'" type="button" class="btn btn-success">Details</a><button type="button" class="btn btn-danger" onClick="deletePlan(\''+value.code+'\')">Delete</button></div></td></tr>';
                        });
                    }else{
                        tableBodyHtml += '<tr><td colspan="6">No plans found in the database</td></tr>';
                    }
                }else{
                    tableBodyHtml += '<tr><td colspan="6">No plans found in the database</td></tr>';
                }

                $('#plan-list tbody').html(tableBodyHtml);
            }else{
                addErrorMessage('Error!', 'Please refresh your browser!');
            } 
        },
        error: function(response){
            addErrorMessage('Error!', 'Please refresh your browser!');
        }
    });    
}

var refreshPlanDetails = function(code){

    $.ajax({
        url: '/admin/plans/get-plan',
        method: 'post',
        data: {code: code},
        success: function(responseJSON){ 
            var tableBodyHtml = '<tr><th>ID</th><th>Name</th><th>Description</th><th>Difficulty</th></tr>';
            response = $.parseJSON(responseJSON);
            if (typeof response !== null) {
                if (response.result === true) {
                    tableBodyHtml += '<tr><td>' + response.plan.id + '</td><td>' + response.plan.plan_name + '</td><td>' + response.plan.plan_description + '</td><td>' + response.plan.plan_difficulty_text + '</td></tr>';

                }else{
                    tableBodyHtml += '<tr><td colspan="6">No plans found in the database</td></tr>';
                }

                $('#plan-details tbody').html(tableBodyHtml);
            }else{
                addErrorMessage('Error!', 'Please refresh your browser!');
            }   
        },
        error: function(response){
            addErrorMessage('Error!', 'Please refresh your browser!');
        }
    });    
}

var refreshDayList = function(code){

    $.ajax({
        url: '/admin/plans/get-days',
        method: 'post',
        data: {code: code},
        success: function(responseJSON){ 
            response = $.parseJSON(responseJSON);
            if (typeof response !== null) {
                if (response.result === true) {
                    var dayListBody = '<div id="dayList" class="row">';
                    var daySelectOptions = '';
                    var exerciseBody = '';
                    if (Array.isArray(response.days) && response.days.length > 0) {
                        $.each(response.days, function(dayIndex, day){
                            exerciseBody = '';
                            if (Array.isArray(day.exercises) && day.exercises.length > 0) {
                                $.each(day.exercises, function(exerciseIndex, exercise){
                                    exerciseBody += '<div>'+ exercise.name + ' - ' + formatTohhmmss(exercise.exercise_duration) + '</div>'
                                });
                            }else{
                                exerciseBody = '<div>No exercises in day yet</div>';
                            }

                            dayListBody += '<div id="day-' + dayIndex + '" class="col-sm-4"><input type="hidden" name="code" value="' + day.code + '"><div class="box box-info"><div class="box-header with-border"><h3 class="box-title">'+ day.day_name +'</h3><div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" onClick="deleteDayFromPlan(\''+ day.code +'\');"><i class="fa fa-times"></i></button><i class="fa fa-bars handle"></i></div></div><div class="box-body day-box-body"><div class="day-box-body-scroll">'+exerciseBody+'</div><button class="btn btn-success" onClick="editDay(\''+ day.code +'\');">Edit day</button></div></div></div>';
                        });

                        dayListBody += '</div>';
                        $('#plan-day-container').html(dayListBody);

                        Sortable.create(dayList, {
                          handle: '.handle',
                          animation: 150,
                          scroll: true,
                          scrollSensitivity: 100,
                          scrollSpeed: 10,
                          onUpdate: function (e) {
                            var code1 = $('#day-' + e.oldIndex + ' input[name="code"]').val();
                            var code2 = $('#day-' + e.newIndex + ' input[name="code"]').val();

                            $.ajax({
                                url: '/admin/plans/change-days-order',
                                method: 'post',
                                data: {code1: code1, code2: code2},
                                success: function(responseJSON){ 
                                    response = $.parseJSON(responseJSON);
                                    if (typeof response !== null) {
                                        if (response.result === true) {
                                            refreshDayList(plan);
                                            addSuccessMessage('Days edited!', 'You have just successfully edited the days!');
                                        }else{
                                            addErrorMessage('Error during sorting!', 'Please refresh your browser and try again!');
                                        }
                                    }    
                                },
                                error: function(response){

                                }
                            });

                          }
                        }); 
                    }else{
                        var dayListBody = '<div id="dayList" class="row">';
                        dayListBody += '<div class="col-sm-12"><div class="box box-danger"><div class="box-header with-border"><h3 class="box-title">The plan has no days added.</h3></div><div class="box-body">Add at least one day so the user could start a new healthy life.</div></div></div>';
                        dayListBody += '</div>';
                        $('#plan-day-container').html(dayListBody);
                    }                   
                }else{
                    var dayListBody = '<div id="dayList" class="row">';
                    dayListBody += '<div class="col-sm-12"><div class="box box-danger"><div class="box-header with-border"><h3 class="box-title">The plan has no days added.</h3></div><div class="box-body">Add at least one day so the user could start a new healthy life.</div></div></div>';
                        dayListBody += '</div>';
                        $('#plan-day-container').html(dayListBody);
                }
            } 
        },
        error: function(response){
            addErrorMessage('Error!', 'Please refresh your browser!');
        }
    });    
}

var editPlan = function(code){
    $.ajax({
        url: '/admin/plans/get-plan',
        method: 'post',
        data: {code: code},
        success: function(responseJSON){ 
            response = $.parseJSON(responseJSON);
            if (typeof response !== null) {
                if (response.result === true) {
                    $('#edit-plan-form input[name="code"]').val(response.plan.code);
                    $('#edit-plan-form input[name="name"]').val(response.plan.plan_name);
                    $('#edit-plan-form textarea[name="description"]').val(response.plan.plan_description);
                    $('#edit-plan-form select[name="difficulty"]').val(response.plan.plan_difficulty);
                    toggleEditPlanModal();
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

var deletePlan = function(code, onDetails = 0){
    $('#delete-plan-modal').modal('show');
    $("#yes-delete-plan").on("click", function(){
        $.ajax({
            url: '/admin/plans/delete',
            method: 'post',
            data: {code: code},
            success: function(responseJSON){ 
                response = $.parseJSON(responseJSON);
                if (typeof response !== null) {
                    if (response.result === true) {
                        if (onDetails === 1) {
                            window.location.replace("/admin/plans");
                        };
                        $('#delete-plan-modal').modal('hide');
                       //REFRESH PLAN LIST
                        refreshPlanList();
                        //ADD SUCCESS MESSAGE
                        addSuccessMessage('Plan deleted!', 'You have just successfully deleted plan!');
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

var deleteDayFromPlan = function(code){
    $('#delete-day-modal').modal('show');
    $("#yes-delete-day").on("click", function(){
        $.ajax({
            url: '/admin/plans/delete-day',
            method: 'post',
            data: {code: code},
            success: function(responseJSON){ 
                response = $.parseJSON(responseJSON);
                if (typeof response !== null) {
                    if (response.result === true) {
                        $('#delete-day-modal').modal('hide');
                       //REFRESH PLAN LIST
                        refreshDayList(plan);
                        //ADD SUCCESS MESSAGE
                        addSuccessMessage('Day deleted!', 'You have just successfully deleted a day!');
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

var deleteExerciseFromDay = function(code){
    $('#delete-exercise-modal').modal('show');
    $("#yes-delete-exercise").on("click", function(){
        $.ajax({
            url: '/admin/plans/delete-exercise',
            method: 'post',
            data: {code: code},
            success: function(responseJSON){ 
                response = $.parseJSON(responseJSON);
                if (typeof response !== null) {
                    if (response.result === true) {
                        $('#delete-exercise-modal').modal('hide');
                        //REFRESH DAY LIST
                        refreshDayList(plan);
                        //REFRESH EDIT DAY
                        refreshEditDay($('#add-exercise-to-day-form input[name="code"]').val());
                        //ADD SUCCESS MESSAGE
                        addSuccessMessage('Exercise deleted!', 'You have just successfully deleted an exercise!');
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

$('#add-plan-form').submit(function(e){
    e.preventDefault();

    var myForm = document.getElementById('add-plan-form');
    var form_data = new FormData(myForm);

    $.ajax({
        url: '/admin/plans/add',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        beforeSend: function(){
            $('#add-plan-button').html('<i class="fa fa-refresh fa-spin fa-fw"></i>').fadeIn();
        },
        complete: function(){
            $('#add-plan-button').html('Add plan').fadeIn();
        },
        success: function(responseJSON){ 
            response = $.parseJSON(responseJSON);
            if (typeof response !== null) {
                if (response.result === true) {
                    //REFRESH PLAN LIST
                    refreshPlanList();
                    //CLEAR FORM
                    $('#add-plan-form')[0].reset();
                    //HIDE MODAL
                    toggleAddPlanModal();
                    //ADD SUCCESS MESSAGE
                    addSuccessMessage('New plan added!', 'You have just successfully added a new plan to the webapp!');
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

//EDIT PLAN
$('#edit-plan-form').submit(function(e){
    e.preventDefault();

    var myForm = document.getElementById('edit-plan-form');
    var form_data = new FormData(myForm);

    $.ajax({
        url: '/admin/plans/edit',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        beforeSend: function(){
            $('#edit-plan-button').html('<i class="fa fa-refresh fa-spin fa-fw"></i>').fadeIn();
        },
        complete: function(){
            $('#edit-plan-button').html('Edit plan').fadeIn();
        },
        success: function(responseJSON){ 
            response = $.parseJSON(responseJSON);
            if (typeof response !== null) {
                if (response.result === true) {
                    if (typeof plan !== 'undefined') {
                        //REFRESH PLAN DETAILS
                        refreshPlanDetails(plan);
                    }else{
                        //REFRESH PLAN LIST
                        refreshPlanList();
                    }
                    //CLEAR FORM
                    $('#edit-plan-form')[0].reset();
                    //HIDE MODAL
                    toggleEditPlanModal();
                    //ADD SUCCESS MESSAGE
                    addSuccessMessage('Plan edited!', 'You have just successfully edited a plan!');
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

//add day to plan
$('#add-day-form').submit(function(e){
    e.preventDefault();

    var myForm = document.getElementById('add-day-form');
    var form_data = new FormData(myForm);

    $.ajax({
        url: '/admin/plans/add-day',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        beforeSend: function(){
            $('#add-day-button').html('<i class="fa fa-refresh fa-spin fa-fw"></i>').fadeIn();
        },
        complete: function(){
            $('#add-day-button').html('Add day').fadeIn();
        },
        success: function(responseJSON){ 
            response = $.parseJSON(responseJSON);
            if (typeof response !== null) {
                if (response.result === true) {
                    //REFRESH DAY LIST
                    refreshDayList(plan);
                    //CLEAR FORM
                    $('#add-day-form')[0].reset();
                    //HIDE MODAL
                    toggleAddPlanDayModal();
                    //ADD SUCCESS MESSAGE
                    addSuccessMessage('New day added!', 'You have just successfully added a new day!');
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

var refreshEditDay = function(code){
    $.ajax({
        url: '/admin/plans/get-day',
        method: 'post',
        data: {code: code},
        success: function(responseJSON){ 
            response = $.parseJSON(responseJSON);
            if (typeof response !== null) {
                if (response.result === true) {
                    $('#edit-day-form input[name="code"]').val(response.day.code);
                    $('#add-exercise-to-day-form input[name="code"]').val(response.day.code);
                    $('#edit-day-form input[name="name"]').val(response.day.day_name);

                    var exerciseListBody = '<div id="exerciseList" class="row">';
                    var exerciseSelectOptions = '';
                    if (Array.isArray(response.day.exercises.selected) && response.day.exercises.selected.length > 0) {
                        $.each(response.day.exercises.selected, function(exerciseIndex, exercise){
                            exerciseListBody += '<div id="exercise-' + exerciseIndex + '" class="col-sm-6"><input type="hidden" name="code" value="' + exercise.code + '"><div class="box box-info"><div class="box-header with-border"><h3 class="box-title">'+ exercise.name +' - ' + formatTohhmmss(exercise.exercise_duration) + '</h3><div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" onClick="deleteExerciseFromDay(\''+exercise.code+'\', \''+code+'\');"><i class="fa fa-times"></i></button><i class="fa fa-bars handle"></i></div></div></div></div>';
                        });

                        exerciseListBody += '</div>';
                        $('#exercise-container').html(exerciseListBody);

                        Sortable.create(exerciseList, {
                          handle: '.handle',
                          animation: 150,
                          scroll: true,
                          scrollSensitivity: 100,
                          scrollSpeed: 10,
                          onUpdate: function (e) {
                            var code1 = $('#exercise-' + e.oldIndex + ' input[name="code"]').val();
                            var code2 = $('#exercise-' + e.newIndex + ' input[name="code"]').val();

                            $.ajax({
                                url: '/admin/plans/change-exercises-order',
                                method: 'post',
                                data: {code1: code1, code2: code2},
                                success: function(responseJSON){ 
                                    response = $.parseJSON(responseJSON);
                                    if (typeof response !== null) {
                                        if (response.result === true) {
                                            refreshEditDay($('#add-exercise-to-day-form input[name="code"]').val());
                                            refreshDayList(plan);
                                            addSuccessMessage('Exercises edited!', 'You have just successfully edited the exercises!');
                                        }else{
                                            addErrorMessage('Error during sorting!', 'Please refresh your browser and try again!');
                                        }
                                    }    
                                },
                                error: function(response){

                                }
                            });
                          }
                        });
                    }else{
                        exerciseListBody += '<div class="col-sm-12"><div class="box box-danger"><div class="box-header with-border"><h3 class="box-title">Not any exercise added.</h3></div><div class="box-body">Add at least one exercise so the user could start a new healthy life.</div></div></div>';
                        exerciseListBody += '</div>';
                        $('#exercise-container').html(exerciseListBody);
                    }
                    
                    if (Array.isArray(response.day.exercises.not_selected) && response.day.exercises.not_selected.length > 0) {
                        $.each(response.day.exercises.not_selected, function(index, value){
                            exerciseSelectOptions += '<option value="' + value.code + '">' + value.name + '</option>';
                        });
                    }else{
                        exerciseSelectOptions += '<option value="0">No more exercises to add</option>';
                    }

                    $('#select-exercise-to-day').html(exerciseSelectOptions);

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

var editDay = function(code){
    refreshEditDay(code);
    toggleEditDayModal();
}

//EDIT DAY
$('#edit-day-form').submit(function(e){
    e.preventDefault();

    var myForm = document.getElementById('edit-day-form');
    var form_data = new FormData(myForm);

    $.ajax({
        url: '/admin/plans/edit-day',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        beforeSend: function(){
            $('#edit-day-button').html('<i class="fa fa-refresh fa-spin fa-fw"></i>').fadeIn();
        },
        complete: function(){
            $('#edit-day-button').html('Edit day').fadeIn();
        },
        success: function(responseJSON){ 
            response = $.parseJSON(responseJSON);
            if (typeof response !== null) {
                if (response.result === true) {
                    //REFRESH Day LIST
                    refreshDayList(plan);
                    //ADD SUCCESS MESSAGE
                    addSuccessMessage('Day edited!', 'You have just successfully edited a day!');
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



$('#add-exercise-to-day-form').submit(function(e){
    e.preventDefault();
    var myForm = document.getElementById('add-exercise-to-day-form');
    var formData = new FormData(myForm);

    $.ajax({
        url: '/admin/plans/add-exercise',
        cache: false,
        contentType: false,
        processData: false,
        data: formData,
        type: 'post',
        beforeSend: function(){
            $('#add-exercise-to-day-button').html('<i class="fa fa-refresh fa-spin fa-fw"></i>').fadeIn();
        },
        complete: function(){
            $('#add-exercise-to-day-button').html('Add exercise').fadeIn();
        },
        success: function(responseJSON){ 
            response = $.parseJSON(responseJSON);
            if (typeof response !== null) {
                if (response.result === true) {
                    //REFRESH DAY LIST
                    refreshDayList(plan);
                    //REFRESH DAY EDIT
                    refreshEditDay($('#add-exercise-to-day-form input[name="code"]').val());
                    //ADD SUCCESS MESSAGE
                    addSuccessMessage('Exercise added to day!', 'You have just successfully added an exercise to the day!');
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
