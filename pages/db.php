<?php

//$sql = "UPDATE `cariler` SET `cari_no`=concat('1',LPAD(cari_ref,2,'0'),LPAD(cari_no,7,'0')) WHERE 1";
//$sql = "UPDATE adresler as a INNER JOIN cariler as c on a.adres_cariRef = c.cari_no SET a.adres_cariRef=concat('1',LPAD(c.cari_ref,2,'0'),LPAD(c.cari_no,7,'0')) WHERE 1";
$sql = "SELECT * FROM `cariler` where 1 limit 1";
$result = $mysqli -> query($sql);
$cariler = result_array($result);
var_dump($cariler);

