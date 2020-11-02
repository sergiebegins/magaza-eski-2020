<?php
date_default_timezone_set('Europe/Istanbul');
session_start();
if(empty($_SESSION['magaza'])){
    $_SESSION['magaza']=1;
}
define("service_url", "https://misshaturkiye.net/Service/request.php");
require_once ('../core/mysqlConnect.php');
require_once ('../helper.php');
require_once ('../core/validation.php');
require_once ('../core/curl.php');