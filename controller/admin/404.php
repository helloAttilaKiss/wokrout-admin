<?php 

$view = new AdminView();
$view->setView('404.php');

$view->setParam('activeMenu', '404');

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
?>