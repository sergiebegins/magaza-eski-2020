<?php
session_start();
require_once ('../core/mysqlConnect.php');
require_once ('../helper.php');
$cari_inp = $_GET["cari_inp"];

session_unset();
// destroy the session
session_destroy();

session_start();
if(strpos($cari_inp,'@')){
    $sql = "SELECT * FROM cariler where cari_eposta='".$cari_inp."' and cari_aktif = 1 limit 1";
    $result = $mysqli -> query($sql);
    $cariler = result_array($result);
}
else{
    $sql = "SELECT * FROM cariler where cari_telefon='".$cari_inp."' and cari_aktif = 1 limit 1";
    $result = $mysqli -> query($sql);
    $cariler = result_array($result);
}
if(!empty($cariler)){
    $cariler = $cariler[0];
    $_SESSION["cari_no"] = intval($cariler['cari_no']);
    $_SESSION["cari_ad"] = $cariler['cari_ad']." ".$cariler['cari_soyad'];

    echo json_encode(array('status'=>1));
}else{
    $_SESSION["msg"]= 'Müşteri Bulunamadı!';
    echo json_encode(array('status'=>1));
}

