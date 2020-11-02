<?php
session_start();
date_default_timezone_set('Europe/Istanbul');
require_once ('../core/mysqlConnect.php');
require_once ('../helper.php');
$personel = intval($_POST['personel']);
$cari = $_SESSION['cari_no'];
$cari_ad = $_SESSION['cari_ad'];
$sepet = $_SESSION['sepet'];
$faturaNo = $_SESSION['fatura_no'];


if($personel<1){
    $_SESSION["msg"] = "Lütfen Personel Seçiniz!";
    header("Location: http://localhost/magaza/index.php");
}

$sql = "SELECT * FROM odemeSecenekleri WHERE os_magazaRef=".$_SESSION['magaza']." and os_aktif=1";
$odsec = $mysqli -> query($sql);
$fatura_tutar = round(($sepet['toplam']-$sepet['indirim']),2);
$odemeSecenekler = result_array($odsec);
foreach ($odemeSecenekler as $k=>$v){
    $odemeSecenekleri[$v["os_no"]] = $v;
}

$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
try {
    $sepet['toplam'] = strval($sepet['toplam']);
    $sepet['indirim'] = strval($sepet['indirim']);
        $sql2 = "INSERT INTO `faturalar`
                (
                 `fatura_tip`,
                  `fatura_faturaNo`,
                        `fatura_magazaRef`,
                         `fatura_personelRef`,
                         `fatura_tutar`,
                          `fatura_cariRef`,
                          `fatura_full`,
                          `fatura_durum`,
                          `fatura_tarih`,
                           `fatura_ftarih`,
                           `fatura_fadres`,
                            `fatura_not`,
                            `fatura_sepet`
                   )
                    VALUES (
                        'ps',
                        '".$faturaNo."',
                        ".$_SESSION['magaza'].",
                        ".$personel.",
                        ".$fatura_tutar.",
                        '".$cari."',
                        '".$cari_ad."',
                        0,
                        ".time().",
                        ".time().",
                        'adres',
                        'not',
                        '".json_encode($sepet,JSON_UNESCAPED_UNICODE)."'
                    );";

         $fatura =[
             'ps',
             $faturaNo,
             $_SESSION['magaza'],
                        $personel,
                        $fatura_tutar,
                        $cari,
                        $cari_ad,
                        0,
                        time(),
                        time(),
                        'adres',
                        'not',
                        json_encode($sepet,JSON_UNESCAPED_UNICODE)
         ];


        $result2 = $mysqli -> query($sql2);
        $faturaLastID = $mysqli->insert_id;



        $sql3 = "INSERT INTO `cariHareketler`
                (
                `ch_tip`,
                `ch_faturaID`,
                `ch_faturaNo`,
                `ch_os`,
                 `ch_magazaRef`,
                 `ch_cariRef`,
                   `ch_personelRef`,
                    `ch_tutar`,
                         `ch_tarih`,
                           `ch_onay`,
                           `ch_aciklama`
                           )
            VALUES";
        $virgul="";
        foreach ($_SESSION['odeme'] as  $k=>$v){

            if($v>0){
                $os = explode('-',$k);
                $osec = ($odemeSecenekleri[$os[0]]['os_odemeSekli'] == 'Nakit')?'Nakit':"KK";
                $sql3 .=$virgul;
                $sql3 .= " (
                     '".$osec."',
                     ".$faturaLastID.",
                     '".$faturaNo."',
                     ".$os['0'].",
                     ".$_SESSION['magaza'].",
                     ".$cari.",
                     ".$personel.",
                     ".$v.",
                     '".date('Y-m-d h:i:s',time())."',
                     2,
                     '".$os['1']."(".$v.")'"."
            )";
                $virgul =",";
            }


        }
         $sql3.=";";
        $result3 = $mysqli -> query($sql3);


        $sql4="INSERT INTO `stokHareketler`
                (
                 `sh_tip`,
                  `sh_tur`,
                   `sh_faturaID`,
                   `sh_faturaNo`,
                   `sh_urunNo`,
                   `sh_magazaRef`,
                   `sh_personelRef`,
                    `sh_cariRef`,
                     `sh_fiyat`,
                     `sh_satisFiyat`,
                     `sh_tutar`,
                     `sh_adet`,     
                      `sh_tarih`,
                      `sh_kampanyaRef`
                        ) VALUES";
        $virgul="";
        foreach ($sepet['sepet'] as $k=>$v){

            $sql4 .=$virgul;
            $sql4 .= "(
                        'ps',
                        'satis',
                        ".$faturaLastID.",
                        '".$faturaNo."',
                        ".$v['urun'].",
                        ".$_SESSION['magaza'].",
                        ".$personel.",
                        ".$cari.",
                        ".$v['sf'].",
                        ".($v['sf']-$v['indirim']).",
                        ".($v['sf']-$v['indirim'])*$v['adet'].",
                        ".$v['adet'].",
                        '".date('Y-m-d h:i:s',time())."',
                        ".$v['kmp']."
                    )";
            $virgul =",";
        }
        $sql4.=";";
        $result4 = $mysqli -> query($sql4);


        $sql5 ='DELETE FROM `temp` WHERE `temp_cari`='.$cari;
        $result5 = $mysqli -> query($sql5);
        $sql6 ='DELETE FROM `tempKupon` WHERE `cari_no`='.$cari;
        $result6 = $mysqli -> query($sql6);


    if(!$result2 || !$result3 || !$result4 || !$result5 || !$result6){
        throw new Exception();
    }else{
        $params = ['fatura'=>$fatura,'ch'=>$_SESSION['odeme'],'sh'=>$sepet['sepet'],'magaza'=>$_SESSION['magaza'],'req'=>'magazaTransfer'];
        try {
            $sonuc = curlPost(service_url,$params);
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
    }

}catch (\Exception $e) {

    echo $e->getMessage();
    $mysqli->rollback();
    throw $e;
}

$mysqli->commit();
$mysqli->close();

session_unset();
session_destroy();

header("Location: http://localhost/magaza/faturaPdf.php?faturaNo=".$faturaLastID);


