<?php

require_once 'config.php';

if (($_SERVER['PHP_AUTH_USER'] != AUTH_USER) || ($_SERVER['PHP_AUTH_PW'] != AUTH_PASS))
{
  header('WWW-Authenticate: Basic Realm="Site Manager 2"');
  header('HTTP/1.0 401 Unauthorized');
  print('You must provide the proper credentials!');
  exit;
}

require_once 'classes/View.class.php';
require_once 'classes/Controller.class.php';

$view = new View;
$ctrl = new Controller($view);
$ctrl->process_request();
?>
