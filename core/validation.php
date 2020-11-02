<?php
function validate($params){
    $sonuc = 1;
    foreach ($params as $k=>$v){
        if($sonuc){
            if(sizeof($v)<2){
                $sonuc *= $k($v[0]);
            }
            else{
                if(($k == 'match' && sizeof($v) == 2)){
                    $sonuc *= $k($v);
                }else{
                    foreach ($v as $k2=>$v2){
                        if($sonuc){
                            $sonuc *= $k($v2);
                        }
                    }
                }
            }
        }
    }
    return $sonuc;
}

function email($email){
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["msg"] = 'Email Bilgilerinizi Kontrol Ediniz!';
        return 0;
    }
    return 1;
}

function phone($phone){
    if(!preg_match('/^[0-9]{10}+$/', $phone)) {
        $_SESSION["msg"] = 'Telefon Bilgilerinizi Kontrol Ediniz!';
        return 0;
    }
    return 1;
}

function required($temp){
    if(empty($temp)){
        $_SESSION["msg"] = 'Zorunlu Alanları Kontrol Ediniz!';
        return 0;
    }
    return 1;
}

function match($temp){
    if($temp[0]!=$temp[1]){
        echo json_encode(array('status'=>0,'msg'=>'Şifreleriniz uyuşmuyor lütfen kontrol edip tekrar deneyiniz!'));
        return 0;
    }
    return 1;
}