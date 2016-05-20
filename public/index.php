<?php
define('DS', DIRECTORY_SEPARATOR);
define('BASE_PATH', realpath(dirname(__FILE__).DS.'..').DS);
define('APP_PATH', realpath(dirname(__FILE__).DS.'..'.DS.'app').DS);

//require_once BASE_PATH.'vendor/autoload.php';

$app  = new Yaf\Application(APP_PATH."conf/app.ini");
$app = $app->bootstrap();
$app = $app->run();
