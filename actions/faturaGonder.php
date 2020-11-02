<?php

if($_POST['fatura_id']){
    require_once ('../core/config.php');

    $fatura_id = intval($_POST['fatura_id']);

    $sql = "SELECT * FROM `faturalar` as f INNER JOIN cariler as c on f.fatura_cariRef=c.cari_no WHERE f.fatura_id = ".$fatura_id;
    $result = $mysqli -> query($sql);

    $fatura = result_array($result);
    $fatura = $fatura[0];

    $sql3 = "SELECT * FROM `cariHareketler`  WHERE ch_faturaID = ".$fatura_id;
    $result3 = $mysqli -> query($sql3);

    $ch = result_array($result3);

    $sql4 = "SELECT * FROM `stokHareketler` WHERE sh_faturaID= ".$fatura_id;
    $result4 = $mysqli -> query($sql4);
    $sh = result_array($result4);


    $params = ['fatura'=>$fatura,'ch'=>$ch,'sh'=>$sh];
    if(!$fatura['fatura_online']){
        $params['magaza'] = $_SESSION['magaza'];
        $params['req'] = 'magazaTransfer';
        try {
            $sonuc = curlPost(service_url,$params);
            var_dump($sonuc);
            exit();
            if(!$sonuc){
                throw new Exception(0);
            }else{
                $sql2 = "UPDATE `faturalar` SET `fatura_online`=1 WHERE fatura_id=".$fatura_id;
                $result2 = $mysqli -> query($sql2);
                echo 1;
            }
        }catch(Exception $e){
            echo 0;
        }


    }else{
        $_SESSION["msg"]= 'Fatura Daha Önce Gönderilmiş!';
        header("Location: http://localhost/magaza");
    }


}else{
    $_SESSION["msg"]= 'Fatura Bulunamadı!';
    header("Location: http://localhost/magaza");
}
