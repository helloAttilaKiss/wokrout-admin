<?php

//Require config, views, models, core files and basic functions
require 'require.php';
session_start();

if (DEV) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	//require('view/email/userNotification.php');
	//exit;
}

//Clean url from / multiplications
$uri = preg_replace('/\/{2,}/', '/',  $_SERVER['REQUEST_URI']);
if ($uri !==  $_SERVER['REQUEST_URI']) {
	header('Location: ' . WEB_ROOT . $uri);
	exit;
}

//Create own get var, leave the $_GET untouched
$get = array_values(array_filter( explode('/', $_SERVER['REQUEST_URI']), function($value) {
	return $value !== '';
}));

//Cut out ?get variables from the custom $get variable
foreach ($get as $key => &$val) {
    $pos = strpos($val, '?');   
    if ($pos === 0) {
        unset($get[$key]);
    }elseif($pos !== false) {
        $val = substr($val, 0, $pos);
    }
}

//Handle routes
//BASE routes
if (!isset($get[0]) || $get[0] == 'home') {
	//While there's nothin on the homepage just redirect to the admin dashboard!
    header('Location: ' . WEB_ROOT . '/admin/dashboard');
    exit;
}else{
	//ADMIN related routes
	if ($get[0] === 'admin') {
		if (!isset($get[1]) || $get[1] == 'dashboard') {
			require 'controller/admin/dashboard.php';
			exit;
		}else{
			switch ($get[1]) {
				case 'users':
					require 'controller/admin/users.php';
					exit;
				case 'plans':
					require 'controller/admin/plans.php';
					exit;
			}
		}
		require 'controller/admin/404.php';
		exit;
	}
	//USER related routes
	elseif($get[0] === 'user'){
		header('Location:' . WEB_ROOT . '/admin/dashboard');
		exit;
		//For future purposes
	}
}

//In case the requested route is missing: 404
require 'controller/admin/404.php';