<?php
session_start();

define("ROOT", dirname(__FILE__));

require (ROOT . "/app/Application.php");

date_default_timezone_set("Asia/Shanghai");

$config = include_once ROOT . '/config/web.php';

$app = new Application($config);

$app->run();


