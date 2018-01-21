<?php 

//DEV ENVIRONMENT
define('DEV', false);
//LOCALE SETTINGS
date_default_timezone_set("Europe/Budapest");
setlocale(LC_ALL, 'hu_HU.utf8');
//DEFAULT FULL DIRECTORY PATH (include the trailing /)
define('DIR_ROOT', '/home/helloattilakiss/websites/workout-admin/');
//DEFAULT WEB ROOT (exclude the trailing /)
define("WEB_ROOT", "https://virtuagym.coding-you.com");
//DATABASE CREDITENTIALS
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', '');
define('DB_PASSWORD', '');
define('DB_DATABASE', '');
//EMAIL DEFAULTS
define("EMAIL_INFO", 'workout@coding-you.com');
define("EMAIL_INFO_NAME", "Workout website");
define("EMAIL_REPLY", 'reply@coding-you.com');
define('EMAIL_REPLY_NAME', 'Workout website');
//SMTP SETTINGS
define('USE_SMTP', true);
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('SMTP_PORT', '465');
define('SMTP_SECURE', 'ssl');
//PHP MAILER
define('PHPMAILER_DEBUG', 0);

?>