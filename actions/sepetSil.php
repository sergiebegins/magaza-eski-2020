<?php
session_start();
require_once ('../core/mysqlConnect.php');
require_once ('../helper.php');

if(isset($_SESSION["cari_no"]) && intval($_SESSION["cari_no"])>0){
    $cari_no =intval($_SESSION["cari_no"]);
    $urun_no = $_GET["urun"];

    $sql3 = "DELETE FROM temp WHERE temp_cari=".$cari_no." and temp_urun=".$urun_no;
    $result = $mysqli -> query($sql3);
    if($result){
        echo json_encode(array('status'=>1));
    }
}