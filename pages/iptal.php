<?php
    session_start();
if(isset($_SESSION["cari_no"]) && intval($_SESSION["cari_no"])>0){


    $sql5 ='DELETE FROM `temp` WHERE `temp_cari`='.intval($_SESSION["cari_no"]);
    $result5 = $mysqli -> query($sql5);

    $sql6 ='DELETE FROM `tempKupon` WHERE `cari_no`='.intval($_SESSION["cari_no"]);
    $result6 = $mysqli -> query($sql6);



    session_unset();

    session_destroy();

    header("Location: http://localhost/magaza");



}

?>


