<?php 

//POST for Ajax
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset($get[2])) {
        switch ($get[2]) {
            case 'add':
                echo addPlan();
                break;
            case 'get-plan':
                echo getPlan();
                break;
            case 'get-plans':
                echo getPlans();
                break;
            case 'edit':
                echo editPlan();
                exit;
            case 'delete':
                echo deletePlan();
                exit;
            case 'add-day':
                echo addDay();
                exit;
            case 'get-days':
                echo getDays();
                exit;
            case 'delete-day':
                echo deleteDay();
                exit;
            case 'get-day':
                echo getDay();
                exit;
            case 'edit-day':
                echo editDay();
                exit;
            case 'change-days-order':
                echo changeDaysOrder();
                exit;
            case 'add-exercise':
                echo addExercise();
                exit;
            case 'delete-exercise':
                echo deleteExercise();
                exit;
            case 'change-exercises-order':
                echo changeExercisesOrder();
                exit;
        }
    }
}
//GET for view
else{
    if (isset($get[2])) {
        showPlanDetails($get[2]);
    }else{
        showPage();
    }
}
exit();

function changeExercisesOrder(){
    if (isset($_POST['code1']) && strlen(trim($_POST['code1'])) > 0 && isset($_POST['code2']) && strlen(trim($_POST['code2'])) > 0 && trim($_POST['code1']) !== trim($_POST['code2'])) {
        $code1 = trim($_POST['code1']);
        $code2 = trim($_POST['code2']);

        $baseModel = new BaseModel();
        //Check if exercise 1 and 2 exists in the database
        $exercise1 = $baseModel->getDayExerciseByCode($code1);
        $exercise2 = $baseModel->getDayExerciseByCode($code2);
        if ($exercise1 && $exercise2) {            
            //Everything seems fine we can change the 2 exercises order

            $changeDayExercisesOrder = $baseModel->changeDayExercisesOrder(array('dayId' => $exercise1['day_id'], 'id1' => $exercise1['id'], 'order1' => $exercise1['order'], 'order2' => $exercise2['order']));
            if ($changeDayExercisesOrder) {
                return json_encode(array('result' => true, 'message' => 'Exercises successfully edited!'));
            }else
                return json_encode(array('result' => false, 'message' => 'Something went wrong, please refresh your browser and try again!'));
        }else
            return json_encode(array('result' => false, 'message' => 'One or both of the exercises cannot be found!'));
    }else
        return json_encode(array('result' => false, 'message' => 'Missing exercise info!'));
}

function deleteExercise(){
    if (isset($_POST['code']) && strlen(trim($_POST['code'])) > 0) {
        $code = trim($_POST['code']);
        $baseModel = new BaseModel();
        //Check if exercise exists in the database
        $exercise = $baseModel->getDayExerciseByCode($code);
        if ($exercise) {
            //Everything seems fine we can delete the day from the database
            $deleteExercise = $baseModel->deleteDayExercise(array('id' => $exercise['id'], 'deletedTS' => date('Y-m-d H:i:s')));
            if ($deleteExercise) {
                return json_encode(array('result' => true, 'message' => 'Exercise successfully deleted!'));
            }else
                return json_encode(array('result' => false, 'message' => 'Something went wrong, please refresh your browser and try again!'));
        }else
            return json_encode(array('result' => false, 'message' => 'Exercise cannot be found!'));
    }else
        return json_encode(array('result' => false, 'message' => 'Missing Exercise info!'));
}

function addExercise(){
    if (isset($_POST['exerciseDuration']) && isset($_POST['exercise']) && isset($_POST['code']) && strlen(trim($_POST['exercise'])) > 0 && strlen(trim($_POST['code'])) > 0 && strlen(trim($_POST['exerciseDuration'])) > 0 && intval($_POST['exerciseDuration']) > 0) {

        $exerciseCode = trim($_POST['exercise']);
        $code = trim($_POST['code']);
        $exerciseDuration = intval($_POST['exerciseDuration']);

        $baseModel = new BaseModel();

        $day = $baseModel->getPlanDayByCode($code);
        if ($day) { 
            //Check if exercise exists
            $exercise = $baseModel->getExerciseByCode($exerciseCode);
            if ($exercise) {
                //Check if exercise already added to day
                $exercises = $baseModel->getExercisesByDayId($day['id']);
                if (!$exercises || !in_array($exercise, array_column($exercises, 'exercise_id'))) {
                    $lastExerciseOrder = $baseModel->getLastExerciseOrderByDayId($day['id']);
                    $order = 1;
                    if ($lastExerciseOrder) $order += $lastExerciseOrder;
                    $addExercise = $baseModel->addExerciseToDay(array('exerciseId' => $exercise['id'], 'dayId' => $day['id'], 'exerciseDuration' => $exerciseDuration, 'order' => $order, 'code' => uniqid(), 'status' => 1, 'createdTS' => date('Y-m-d H:i:s')));
                    if ($addExercise) {
                        return json_encode(array('result' => true, 'message' => 'Exercise successfully added!'));
                    }else
                        return json_encode(array('result' => false, 'message' => 'Something went wrong, please refresh your browser and try again!'));
                }
                return json_encode(array('result' => false, 'message' => 'Exercise already added to day'));
            }
            return json_encode(array('result' => false, 'message' => 'No exercise found'));
        }
        return json_encode(array('result' => false, 'message' => 'No day found'));
    }
    return json_encode(array('result' => false, 'message' => 'Something went wrong, please refresh your browser and try again!'));      
}

function editDay(){
    if (isset($_POST['code']) && strlen(trim($_POST['code'])) > 0 && isset($_POST['name']) && strlen(trim($_POST['name'])) > 0) {
        $name = trim($_POST['name']);
        $code = trim($_POST['code']);

        $baseModel = new BaseModel();
        //Check if plan day exists in the database
        $day = $baseModel->getPlanDayByCode($code);
        if ($day) {            
            //Everything seems fine we can edit the plan in the database
            $editDay = $baseModel->editPlanDay(array('name' => $name, 'id' => $day['id']));
            if ($editDay) {
                return json_encode(array('result' => true, 'message' => 'Day successfully edited!'));
            }else
                return json_encode(array('result' => false, 'message' => 'Something went wrong, please refresh your browser and try again!'));
        }else
            return json_encode(array('result' => false, 'message' => 'Day cannot be found!'));
    }else
        return json_encode(array('result' => false, 'message' => 'Missing day info! Please enter all the requested data for the day.'));
}

function changeDaysOrder(){
    if (isset($_POST['code1']) && strlen(trim($_POST['code1'])) > 0 && isset($_POST['code2']) && strlen(trim($_POST['code2'])) > 0 && trim($_POST['code1']) !== trim($_POST['code2'])) {
        $code1 = trim($_POST['code1']);
        $code2 = trim($_POST['code2']);

        $baseModel = new BaseModel();
        //Check if plan day 1 and day 2 exists in the database
        $day1 = $baseModel->getPlanDayByCode($code1);
        $day2 = $baseModel->getPlanDayByCode($code2);
        if ($day1 && $day2) {            
            //Everything seems fine we can change the 2 days order

            $changeDaysOrder = $baseModel->changePlanDaysOrder(array('planId' => $day1['plan_id'], 'id1' => $day1['id'], 'order1' => $day1['order'], 'order2' => $day2['order']));
            if ($changeDaysOrder) {
                return json_encode(array('result' => true, 'message' => 'Days successfully edited!'));
            }else
                return json_encode(array('result' => false, 'message' => 'Something went wrong, please refresh your browser and try again!'));
        }else
            return json_encode(array('result' => false, 'message' => 'One or both of the days cannot be found!'));
    }else
        return json_encode(array('result' => false, 'message' => 'Missing day info!'));
}

function getDay(){
    if (isset($_POST['code']) && strlen(trim($_POST['code'])) > 0){
        $code = trim($_POST['code']);
        $baseModel = new BaseModel();
        $day = $baseModel->getPlanDayByCode($code);
        if ($day) {
            $day['exercises']['selected'] = $baseModel->getExercisesByDayId($day['id']);
            $day['exercises']['not_selected'] = $baseModel->getNotSelectedExercisesByDayId($day['id']);
            return json_encode(array('result' => true, 'day' => $day));
        }
    }
    return json_encode(array('result' => false, 'message' => 'No day found'));
}

function getDays(){
    if (isset($_POST['code']) && strlen(trim($_POST['code'])) > 0){
        $code = trim($_POST['code']);
        $baseModel = new BaseModel();
        $plan = $baseModel->getPlanByCode($code);
        if ($plan) {
            $days = $baseModel->getPlanDaysByPlanId($plan['id']);
            if ($days) {
                foreach ($days as &$day) {
                    $day['exercises'] = $baseModel->getExercisesByDayId($day['id']);
                }
                return json_encode(array('result' => true, 'days' => $days));
            }
        }
    }
    return json_encode(array('result' => false));    
}

function addDay(){
    if (isset($_POST['name']) && isset($_POST['code']) && strlen(trim($_POST['name'])) > 0 && strlen(trim($_POST['code'])) > 0) {
        $name = trim($_POST['name']);
        $code = trim($_POST['code']);
        $baseModel = new BaseModel();

        $plan = $baseModel->getPlanByCode($code);
        if ($plan) {
            $lastDayOrderNumber = $baseModel->getLastDayOrderByPlanId($plan['id']);
            $order = 1;
            if ($lastDayOrderNumber) $order += $lastDayOrderNumber;
            $addDay = $baseModel->addPlanDay(array('planId' => $plan['id'], 'dayName' => $name, 'order' => $order, 'code' => uniqid(), 'status' => 1, 'createdTS' => date('Y-m-d H:i:s')));
            if ($addDay) {
                return json_encode(array('result' => true, 'message' => 'Plan successfully added!'));
            }else
            return json_encode(array('result' => false, 'message' => 'Something went wrong, please refresh your browser and try again!'));
        }
        return json_encode(array('result' => false, 'message' => 'No plan found'));
    }
    return json_encode(array('result' => false, 'message' => 'No plan found'));        
}

function deleteDay(){
    if (isset($_POST['code']) && strlen(trim($_POST['code'])) > 0) {
        $code = trim($_POST['code']);
        $baseModel = new BaseModel();
        //Check if day exists in the database
        $day = $baseModel->getPlanDayByCode($code);
        if ($day) {
            //Everything seems fine we can delete the day from the database
            $deleteDay = $baseModel->deletePlanDay(array('id' => $day['id'], 'deletedTS' => date('Y-m-d H:i:s')));
            if ($deleteDay) {
                return json_encode(array('result' => true, 'message' => 'Day successfully deleted!'));
            }else
                return json_encode(array('result' => false, 'message' => 'Something went wrong, please refresh your browser and try again!'));
        }else
            return json_encode(array('result' => false, 'message' => 'Day cannot be found!'));
    }else
        return json_encode(array('result' => false, 'message' => 'Missing day info! Please enter all the requested data for the day.'));
}


function getPlans(){
    $baseModel = new BaseModel();
    $plans = $baseModel->getPlans();
    if ($plans) {
        foreach ($plans as &$plan) {
            $days = $baseModel->getPlanDaysByPlanId($plan['id']);
            $dayNumber = 0;
            if ($days) $dayNumber = count($days);
            $plan['day_number'] = $dayNumber;
            $plan['plan_difficulty_text'] = getDifficultyLevelsName($plan['plan_difficulty']);
        }
        return json_encode(array('result' => true, 'plans' => $plans));
    }

    return json_encode(array('result' => false));
    
}

function getPlan(){
    if (isset($_POST['code']) && strlen(trim($_POST['code'])) > 0){
        $code = trim($_POST['code']);
        $baseModel = new BaseModel();
        $plan = $baseModel->getPlanByCode($code);
        if ($plan) {
            $plan['plan_difficulty_text'] = getDifficultyLevelsName($plan['plan_difficulty']);
            return json_encode(array('result' => true, 'plan' => $plan));
        }
    }
    return json_encode(array('result' => false, 'message' => 'No plan found'));
}

function addPlan(){
    if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['difficulty']) && strlen(trim($_POST['name'])) > 0 && strlen(trim($_POST['description'])) > 0 && strlen(trim($_POST['difficulty'])) > 0) {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $difficulty = trim($_POST['difficulty']);

        $baseModel = new BaseModel();

        //Everything seems fine we can insert the plan into the database
        $addPlan = $baseModel->addPlan(array('name' => $name, 'description' => $description, 'difficulty' => $difficulty, 'code' => uniqid(), 'status' => 1, 'createdTS' => date('Y-m-d H:i:s')));
        if ($addPlan) {
            return json_encode(array('result' => true, 'message' => 'Plan successfully added!'));
        }else
            return json_encode(array('result' => false, 'message' => 'Something went wrong, please refresh your browser and try again!'));
    }else
        return json_encode(array('result' => false, 'message' => 'Missing plan info! Please enter all the requested data for the plan.'));
}

function editPlan(){
    if (isset($_POST['code']) && strlen(trim($_POST['code'])) > 0 && isset($_POST['name']) && isset($_POST['description']) && isset($_POST['difficulty']) && strlen(trim($_POST['name'])) > 0 && strlen(trim($_POST['description'])) > 0 && strlen(trim($_POST['difficulty'])) > 0) {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $difficulty = trim($_POST['difficulty']);
        $code = trim($_POST['code']);

        $baseModel = new BaseModel();
        //Check if pan email exists in the database
        $plan = $baseModel->getPlanByCode($code);
        if ($plan) {            
            //Everything seems fine we can edit the plan in the database
            $editPlan = $baseModel->editPlan(array('name' => $name, 'description' => $description, 'difficulty' => $difficulty, 'id' => $plan['id']));
            if ($editPlan) {
                $users = $baseModel->getUsersByPlanId($plan['id']);
                foreach ($users as $user) {
                    emailToUser(array('title' => 'Workout plan edited!', 'messageText' => 'One of your workout plans has been edited recently.', 'user' => $user, 'plan' => $plan));
                }
                return json_encode(array('result' => true, 'message' => 'Plan successfully edited!'));
            }else
                return json_encode(array('result' => false, 'message' => 'Something went wrong, please refresh your browser and try again!'));
        }else
            return json_encode(array('result' => false, 'message' => 'Plan cannot be found!'));
    }else
        return json_encode(array('result' => false, 'message' => 'Missing plan info! Please enter all the requested data for the plan.'));
}

function deletePlan(){
    if (isset($_POST['code']) && strlen(trim($_POST['code'])) > 0) {
        $code = trim($_POST['code']);

        $baseModel = new BaseModel();
        //Check if plan email exists in the database
        $plan = $baseModel->getPlanByCode($code);
        if ($plan) {
            //Everything seems fine we can delete the plan into the database
            $deletePlan = $baseModel->deletePlan(array('id' => $plan['id'], 'deletedTS' => date('Y-m-d H:i:s')));
            if ($deletePlan) {
                $users = $baseModel->getUsersByPlanId($plan['id']);
                foreach ($users as $user) {
                    emailToUser(array('title' => 'Workout plan deleted!', 'messageText' => 'One of your workout plans has been edited recently.', 'user' => $user, 'plan' => $plan));
                }
                return json_encode(array('result' => true, 'message' => 'Plan successfully deleted!'));
            }else
                return json_encode(array('result' => false, 'message' => 'Something went wrong, please refresh your browser and try again!'));
        }else
            return json_encode(array('result' => false, 'message' => 'Plan cannot be found!'));
    }else
        return json_encode(array('result' => false, 'message' => 'Missing plan info! Please enter all the requested data for the plan.'));
}

function getDifficultyLevelsName($level = 0){
    switch ($level) {
        case '1':
            return 'Easy';
            break;
        case '2':
            return 'Medium';
            break;
        case '3':
            return 'Hard';
            break;
        default:
            return 'Unknown';
            break;
    }
}

function showPlanDetails($code){
    $baseModel = new BaseModel();

    //Check if plan code exists
    $plan = $baseModel->getPlanByCode($code);
    if (!$plan) {
        require("controller/admin/404.php");
        exit;
    }

    $view = new AdminView();
    $view->setView('planDetails.php');
    $view->setAddonView('planModals.php');

    $view->setParam('activeMenu', 'plans');
    $view->setParam('plan', $plan);
    
    $view->addStyle('bootstrap.min.css');
    $view->addStyle('font-awesome.min.css');
    $view->addStyle('custom.css');
    $view->addStyle('skins.min.css');
    $view->addStyle('ionicons.css');
    $view->addStyle('base.css');

    $view->addHeadJavascript('jquery.min.js');
    $view->addHeadJavascript('bootstrap.min.js');
    $view->addBodyJavascript('custom.min.js');

    $view->addBodyJavascript('base.js');
    $view->addBodyJavascript('plans.js');
    $view->addBodyJavascript('sortable.min.js');

    $view->render();
}

function showPage(){
    $view = new AdminView();
    $view->setView('plans.php');
    $view->setAddonView('planModals.php');

    $view->setParam('activeMenu', 'plans');
    
    $view->addStyle('bootstrap.min.css');
    $view->addStyle('font-awesome.min.css');
    $view->addStyle('custom.css');
    $view->addStyle('skins.min.css');
    $view->addStyle('ionicons.css');
    $view->addStyle('base.css');

    $view->addHeadJavascript('jquery.min.js');
    $view->addHeadJavascript('bootstrap.min.js');

    $view->addBodyJavascript('custom.min.js');

    $view->addBodyJavascript('base.js');
    $view->addBodyJavascript('plans.js');
    $view->addBodyJavascript('sortable.min.js');
    $view->render();

    $view->render();
}