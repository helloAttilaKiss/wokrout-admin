<?php 

//POST for Ajax
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if (isset($get[2])) {
        switch ($get[2]) {
            case 'add':
                echo addUser();
                break;
            case 'get-user':
                echo getUser();
                break;
            case 'get-plans':
                echo getUserPlans();
                break;
            case 'get-users':
                echo getUsers();
                break;
            case 'edit':
                echo editUser();
                exit;
            case 'delete':
                echo deleteUser();
                exit;
            case 'add-plan':
                echo addPlan();
                exit;
            case 'delete-plan':
                echo deletePlan();
                exit;
        }
    }
}
//GET for view
else{
    showPage();
}
exit();

function deletePlan(){
    if (isset($_POST['code']) && strlen(trim($_POST['code'])) > 0) {
        $userPlanCode = trim($_POST['code']);
        $baseModel = new BaseModel();
        //Check if user plan exists in the database
        $userPlan = $baseModel->getUserPlanByCode($userPlanCode);
        if ($userPlan) {
            $removePlanFromUser = $baseModel->deletePlanFromUser(array('Id' => $userPlan['id'], 'deletedTS' => date('Y-m-d H:i:s')));
            if ($removePlanFromUser) {
                $plan = $baseModel->getPlan($userPlan['plan_id']);
                $user = $baseModel->getUser($userPlan['user_id']);
                emailToUser(array('title' => 'Workout plan removal!', 'messageText' => 'One of your workout plans has been removed recently.', 'user' => $user, 'plan' => $plan));
                return json_encode(array('result' => true, 'message' => 'Plan removed from user successfully!'));
            }else
                return json_encode(array('result' => false, 'message' => 'Something went wrong, please refresh your browser and try again!'));
        }else
            return json_encode(array('result' => false, 'message' => 'No user plan found!'));
    }else
        return json_encode(array('result' => false, 'message' => 'Missing info! Please enter all the requested data.'));
}

function addPlan(){
    if (isset($_POST['plan']) && isset($_POST['user']) && strlen(trim($_POST['plan'])) > 0 && strlen(trim($_POST['user'])) > 0) {
        $planCode = trim($_POST['plan']);
        $userCode = trim($_POST['user']);

        $baseModel = new BaseModel();
        //Check if user  exists in the database
        $user = $baseModel->getUserByCode($userCode);
        if ($user) {
            //Check if plan exists in the database
            $plan = $baseModel->getPlanByCode($planCode);
            if ($plan) {
                //check if plan already added
                $userPlans = $baseModel->getUserPlansByUserId($user['id']);
                if (!$userPlans || !in_array($plan['id'], array_column($userPlans, 'id'))) {
                    //Everything seems fine we can insert the user into the database
                    $addPlanToUser = $baseModel->addPlanToUser(array('userId' => $user['id'], 'planId' => $plan['id'], 'code' => uniqid(), 'status' => 1, 'createdTS' => date('Y-m-d H:i:s')));
                    if ($addPlanToUser) {
                        $plan['days'] = $baseModel->getPlanDaysByPlanId($plan['id']);
                        if ($plan['days']) {
                            foreach ($plan['days'] as &$day) {
                                $day['exercises'] = $baseModel->getExercisesByDayId($day['id']);
                            }
                        }

                        emailToUser(array('title' => 'You got a new workout plan!', 'messageText' => 'Check out your new workout plan, you have just been added!', 'user' => $user, 'plan' => $plan));
                        return json_encode(array('result' => true, 'message' => 'Plan added to User successfully!'));
                    }else
                        return json_encode(array('result' => false, 'message' => 'Something went wrong, please refresh your browser and try again!'));
                }else
                    return json_encode(array('result' => false, 'message' => 'Plan already added to user!'));
            }else
                return json_encode(array('result' => false, 'message' => 'No plan found!'));
        }else
            return json_encode(array('result' => false, 'message' => 'No user found!'));
    }else
        return json_encode(array('result' => false, 'message' => 'Missing user info! Please enter all the requested data for the user.'));
}

function getUserPlans(){
    if (isset($_POST['code']) && strlen(trim($_POST['code'])) > 0){
        $code = trim($_POST['code']);
        $baseModel = new BaseModel();
        $user = $baseModel->getUserByCode($code);
        if ($user) {
            $userPlans['selected'] = $baseModel->getUserPlansByUserId($user['id']);
            $userPlans['not_selected'] = $baseModel->getNotSelectedPlansByUserId($user['id']);
            return json_encode(array('result' => true, 'user_plans' => $userPlans));
        }
    }
    return json_encode(array('result' => false, 'message' => 'No user found'));
}

function getUsers(){
    $baseModel = new BaseModel();
    $users = $baseModel->getUsers();
    foreach ($users as &$user) {
        $plans = $baseModel->getUserPlansByUserId($user['id']);
        $planNumber = 0;
        if ($plans) $planNumber = count($plans);
        $user['plan_number'] = $planNumber;
    }
    return json_encode(array('result' => true, 'users' => $users));
}

function getUser(){
    if (isset($_POST['code']) && strlen(trim($_POST['code'])) > 0){
        $code = trim($_POST['code']);
        $baseModel = new BaseModel();
        $user = $baseModel->getUserByCode($code);
        if ($user) {
            return json_encode(array('result' => true, 'user' => $user));
        }
    }
    return json_encode(array('result' => false, 'message' => 'No user found'));
}

function addUser(){
    if (isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['email']) && strlen(trim($_POST['firstName'])) > 0 && strlen(trim($_POST['lastName'])) > 0 && strlen(trim($_POST['email'])) > 0) {
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);
        $email = trim($_POST['email']);

        $baseModel = new BaseModel();
        //Check if user email exists in the database
        $existingUser = $baseModel->getUserByEmail($email);
        if (!$existingUser) {
            //Everything seems fine we can insert the user into the database
            $addUser = $baseModel->addUser(array('firstName' => $firstName, 'lastName' => $lastName, 'email' => $email, 'code' => uniqid(), 'status' => 1, 'createdTS' => date('Y-m-d H:i:s')));
            if ($addUser) {
                return json_encode(array('result' => true, 'message' => 'User successfully added!'));
            }else
                return json_encode(array('result' => false, 'message' => 'Something went wrong, please refresh your browser and try again!'));
        }else
            return json_encode(array('result' => false, 'message' => 'Email address already exists in the database!'));
    }else
        return json_encode(array('result' => false, 'message' => 'Missing user info! Please enter all the requested data for the user.'));
}

function editUser(){
    if (isset($_POST['code']) && strlen(trim($_POST['code'])) > 0 && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['email']) && strlen(trim($_POST['firstName'])) > 0 && strlen(trim($_POST['lastName'])) > 0 && strlen(trim($_POST['email'])) > 0) {
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);
        $email = trim($_POST['email']);
        $code = trim($_POST['code']);

        $baseModel = new BaseModel();
        //Check if user email exists in the database
        $user = $baseModel->getUserByCode($code);
        if ($user) {
            //Check if edited email address hasn't changed or unique in the database
            if ($user['email'] !== $email) {
                $existingUserByEmail = $baseModel->getUserByEmail($email);
                if ($existingUserByEmail) {
                    return json_encode(array('result' => false, 'message' => 'Email address already exists in the database!'));
                    exit;
                }
            }
            
            //Everything seems fine we can edit the user into the database
            $editUser = $baseModel->editUser(array('firstName' => $firstName, 'lastName' => $lastName, 'email' => $email, 'id' => $user['id']));
            if ($editUser) {
                return json_encode(array('result' => true, 'message' => 'User successfully edited!'));
            }else
                return json_encode(array('result' => false, 'message' => 'Something went wrong, please refresh your browser and try again!'));
        }else
            return json_encode(array('result' => false, 'message' => 'User cannot be found!'));
    }else
        return json_encode(array('result' => false, 'message' => 'Missing user info! Please enter all the requested data for the user.'));
}

function deleteUser(){
    if (isset($_POST['code']) && strlen(trim($_POST['code'])) > 0) {
        $code = trim($_POST['code']);

        $baseModel = new BaseModel();
        //Check if user email exists in the database
        $user = $baseModel->getUserByCode($code);
        if ($user) {
            //Everything seems fine we can delete the user into the database
            $deleteUser = $baseModel->deleteUser(array('id' => $user['id'], 'deletedTS' => date('Y-m-d H:i:s')));
            if ($deleteUser) {
                return json_encode(array('result' => true, 'message' => 'User successfully deleted!'));
            }else
                return json_encode(array('result' => false, 'message' => 'Something went wrong, please refresh your browser and try again!'));
        }else
            return json_encode(array('result' => false, 'message' => 'User cannot be found!'));
    }else
        return json_encode(array('result' => false, 'message' => 'Missing user info! Please enter all the requested data for the user.'));
}

function showPage(){

    $view = new AdminView();
    $view->setView('users.php');

    $view->setParam('activeMenu', 'users');

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
    $view->addBodyJavascript('users.js');

    $view->render();
}