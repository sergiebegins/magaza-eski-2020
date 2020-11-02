
<?php
if(isset($_SESSION["cari_no"]) && intval($_SESSION["cari_no"])>0){
    if(isset($_SESSION["sepet"]["toplam"]) && intval($_SESSION["sepet"]["toplam"])>0){
        if(isset($_SESSION['odeme_toplam']) && intval($_SESSION['odeme_toplam'])>0){


$sql = "SELECT * FROM odemeSecenekleri WHERE os_magazaRef=1 and os_aktif=1";
$result = $mysqli -> query($sql);
$result = result_array($result);

$sql2 = "SELECT * FROM personeller WHERE personel_magazaRef=".$_SESSION['magaza']." and personel_aktif=1";
$result2 = $mysqli->query($sql2);
$result2 = result_array($result2);



?>
<style>
    .headerbg{
        background: #008ECF;
    }
    .color-white{
        color: #ffffff;
    }
    .faturaBorder{
        border: solid 1px;
        padding: 13px 16px;
    }
</style>
<form action="./actions/faturaKes.php" method="post">
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12 text-center">
            <h3 class="headerbg color-white pt-2 pb-2">Fatura Özet</h3>
        </div>
        <div class="col-md-12 mt-3">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td style="text-align: center;font-weight: bold;" colspan="3"><?=$_SESSION['cari_ad']?></td>
                </tr>
                <tr>
                    <td>-</td>
                    <td>Sepet Toplam Tutar:</td>
                    <td><?=$_SESSION["sepet"]["toplam"]?></td>
                </tr>
                <?php if($_SESSION["sepet"]["indirim"]){ ?>
                <tr>
                    <td>-</td>
                    <td>Sepet Toplam İndirim:</td>
                    <td><?=$_SESSION["sepet"]["indirim"]?></td>
                </tr>
                <?php } ?>
                <?php
                $toplam = 0;
                foreach ($result as  $k=>$v){
                    $os_miktar = empty($_SESSION['os_'.$v['os_no']])?0:$_SESSION['os_'.$v['os_no']];;
                    ?>
                    <tr>
                        <td><?=$v['os_no']?></td>
                        <td><?=$v['os_aciklama']?></td>
                        <td><?=$os_miktar?></td>
                    </tr>
                    <?
                }
                ?>
                <tr>
                    <td>-</td>
                    <td>Ödenen Toplam Tutar:</td>
                    <td><?=$_SESSION['odeme_toplam']?></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-12 faturaBorder" style="display: none">
            <div class="row">
                <div class="col-md-3">Fatura İsim</div>
                <div class="col-md-9"><input name="fatura_isim" class="form-control"></div>
                <div class="col-md-3 pt-3">Fatura Adresi</div>
                <div class="col-md-9 pt-3"><textarea name="fatura_adres" class="form-control"></textarea></div>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            <div class="row">
                <div class="ml-auto col-md-3 ">
                    <select name="personel" class="form-control">
                        <option value="0">Lütfen Personel Seçiniz!</option>
                        <?php
                        foreach ($result2 as $k=>$v){
                            ?>
                            <option value="<?=$v['personel_no']?>"><?=$v['personel_adSoyad']?></option>
                        <?php
                        }
                        ?>

                    </select>

                </div>
                <div class="col-md-2">
                    <button class="btn btn-success">Fatura Kes</button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>


<?php }
        else{
            $_SESSION["msg"] = "Lütfen Tahsilat Ekleyiniz!";
            header("Location: http://localhost/magaza");
        }
    }
        else{
            $_SESSION["msg"] = "Lütfen Ürün Ekleyiniz!";
            header("Location: http://localhost/magaza");
           }
        }else{
            $_SESSION["msg"] = "Lütfen  Müşteri Seçiniz!";
            header("Location: http://localhost/magaza");
        }
    ?>

