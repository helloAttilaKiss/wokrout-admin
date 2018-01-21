<?php 
showPage();
exit();

function showPage(){
    $baseModel = new BaseModel();

    //Dashboard stats
    $users = $baseModel->getUsers();
    $userNumber = 0;
    if ($users) $userNumber = count($users);

    $plans = $baseModel->getPlans();
    $planNumber = 0;
    if ($plans) $planNumber = count($plans);

    $days = $baseModel->getPlanDays();
    $dayNumber = 0;
    if ($days) $dayNumber = count($days);

    $exercises = $baseModel->getExercises();
    $exerciseNumber = 0;
    if ($exercises) $exerciseNumber = count($exercises);

    $view = new AdminView();
    $view->setView('dashboard.php');

    $view->setParam('activeMenu', 'dashboard');

    $view->setParam('userNumber', $userNumber);
    $view->setParam('planNumber', $planNumber);
    $view->setParam('dayNumber', $dayNumber);
    $view->setParam('exerciseNumber', $exerciseNumber);

    $view->addStyle('bootstrap.min.css');
    $view->addStyle('font-awesome.min.css');
    $view->addStyle('custom.css');
    $view->addStyle('skins.min.css');
    $view->addStyle('ionicons.css');
    $view->addStyle('base.css');

    $view->addHeadJavascript('jquery.min.js');
    $view->addHeadJavascript('bootstrap.min.js');
    $view->addBodyJavascript('custom.min.js');

    $view->render();
}