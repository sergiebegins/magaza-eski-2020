<?php
require_once ('../core/mysqlConnect.php');
require_once ('../helper.php');
if($_POST["kupon_id"]){
    session_start();
    $kupon = intval($_POST["kupon_id"]);

    $sql = "SELECT kupon_id FROM `tempKupon` WHERE cari_no=".$_SESSION['cari_no'];
    $result = $mysqli -> query($sql);

    if(empty($result->num_rows)){
        $sql = "INSERT INTO `tempKupon`( `cari_no`, `kupon_id`) VALUES (".$_SESSION['cari_no'].",".$kupon.")";
        $result2 = $mysqli -> query($sql);
        if($result2){
            echo 1;
        }
    }else{

        $temp = result_array($result);
        $kuponEski = intval($temp[0]["kupon_id"]);
        if($kuponEski != $kupon){
            $sql = "UPDATE `tempKupon` SET `kupon_id`=".$kupon." WHERE cari_no=".$_SESSION['cari_no'];
            $result3 = $mysqli -> query($sql);
            if($result3){
                echo 1;
            }
        }else{
            echo 1;
        }

    }


}
