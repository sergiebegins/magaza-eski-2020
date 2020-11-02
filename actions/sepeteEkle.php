<?php
session_start();
require_once ('../core/mysqlConnect.php');
require_once ('../helper.php');

if(isset($_SESSION["cari_no"]) && intval($_SESSION["cari_no"])>0){
    $cari_no =intval($_SESSION["cari_no"]);
    $barkod = $_GET["barkod"];

    $sql = "SELECT urun_no FROM urunler where (urun_barkod='".$barkod."' or urun_barkod2='".$barkod."')  and urun_aktif = 1 and 
    (urun_tur!='sarf' or urun_tur!='hizmet') limit 1";
    $result = $mysqli -> query($sql);
    $urun = result_array($result);

    if(!empty($urun)){
        $urun = $urun[0];

        $sql2 = "SELECT * FROM temp WHERE temp_cari = ".$cari_no." and temp_urun=".$urun['urun_no']." limit 1";
        $result = $mysqli -> query($sql2);
        $temp = null;
        if($result->num_rows>0){
            $temp = result_array($result);
            $temp=$temp[0];

        }
        $result = null;
        if(empty($temp)){
            $sql3 = "INSERT INTO temp (temp_tip,temp_cari,temp_urun,temp_adet,temp_tarih) VALUES ('satis',".$cari_no.",".$urun['urun_no'].",1,".time().")";

            $result = $mysqli -> query($sql3);
        }
        else{

            $temp['temp_adet']++;
            $sql4 = "UPDATE temp SET temp_adet=".$temp['temp_adet']." WHERE temp_id=".$temp['temp_id'];
            $result = $mysqli -> query($sql4);
        }
        if($result){
            echo json_encode(array('status'=>1));
        }

    }else{
        echo json_encode(array('status'=>0,'msg'=>'Ürün Satışta Değil'));
    }


}else{
    echo json_encode(array('status'=>0,'msg'=>'Lutfen Müşteri Seçiniz'));
}




