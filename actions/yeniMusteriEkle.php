<?php

require_once ('../core/config.php');


$sql2 = "SELECT * FROM cariler WHERE cari_telefon = '".$_POST['cari_telefon']."' or cari_eposta='".$_POST['cari_eposta']."' LIMIT 1";
$result = $mysqli -> query($sql2);

if(empty($result->num_rows)){
    $carino =null;
    $sql1 = "SELECT sira FROM `sayac` WHERE `tip` = 1 ";
    $result1 = $mysqli -> query($sql1);
    if(!empty($result1->num_rows)){
        $carino=result_array($result1)[0]['sira']+1;
    }

    $validate = [
        'required'=>[
            $_POST['cari_ad'],$_POST['cari_soyad'],
            $_POST['cari_eposta'],$_POST['cari_telefon'],
            $_POST['cari_dTarih'],$_POST['cari_personelRef']
        ],
        'email'=>[$_POST['cari_eposta']],
        'phone'=>[$_POST['cari_telefon']]
    ];
    $vs = validate($validate);


    if($carino && $vs){
        $sql3 = "INSERT INTO  cariler (cari_no,cari_tip,cari_ref,cari_personelRef,cari_ad,cari_soyad,cari_telefon,cari_eposta,cari_tarih,cari_dTarih,cari_aktif) VALUES (".$carino.",'m',".$_SESSION['magaza'].",".$_POST['cari_personelRef'].",'".$_POST['cari_ad']."','".$_POST['cari_soyad']."','".$_POST['cari_telefon']."','".$_POST['cari_eposta']."','".time()."','".$_POST['cari_dTarih']."',1)";
        $result = $mysqli -> query($sql3);
        $sql4 = "UPDATE `sayac` SET `sira`=".$carino." WHERE `tip`=1";
        $result4 = $mysqli -> query($sql4);


        $_POST['cari_no'] = $carino;
        $_POST['cari_sifre'] = rand(100000,900000);
        $_POST['req'] = 'uyeKayitEkle';
        $_POST['cari_ref'] = $_SESSION['magaza'];

        try {
        $sonuc = curlPost(service_url,$_POST);
        if(!$sonuc){
            throw new Exception(0);
        }
        $sonuc = intval($sonuc);
        }catch(Exception $e){
            $sonuc = 0;
        }
        if($sonuc<1){
                    $sql4 = "INSERT INTO cariKuyruk (`tip`, `data`,`cari_no`, `durum`) VALUES (1,'".json_encode($_POST,JSON_UNESCAPED_UNICODE)."',".$carino.",0)";
                    $result4 = $mysqli -> query($sql4);
        }
        $_SESSION["msg"] = "işlem başarılı";
    }
    else{

        $_SESSION["msg"] = "işlem hatalı ile karşılastı";
    }

}else{
    $_SESSION["msg"] = "Bu bilgilerde Kayıtlı Bir Üye Bulunmaktadır.";
}



header("Location: http://localhost/magaza");