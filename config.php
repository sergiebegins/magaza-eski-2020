<?php
date_default_timezone_set('Europe/Istanbul');
session_start();
if(empty($_SESSION['magaza'])){
    $_SESSION['magaza']=1;
}
require_once ('./core/mysqlConnect.php');
require_once ('./helper.php');
