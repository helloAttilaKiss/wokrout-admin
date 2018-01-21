<?php 

require 'config.php';
require 'helpers/functions.php';

$cores = glob('core/*.php');
foreach ($cores as $core) {
	require $core;
}

$views = glob('view/*_view.php');
foreach ($views as $view) {
	require $view;
}

$models = glob('model/*_model.php');
foreach ($models as $model) {
	require $model;
}



?>