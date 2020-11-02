<style>
    .register {
        background: -webkit-linear-gradient(left, #3931af, #00c6ff);
        margin-top: 3%;
        padding: 3%;
    }
    .register-right {
        background: #f8f9fa;
        border-top-left-radius: 10% 50%;
        border-bottom-left-radius: 10% 50%;
    }
    .label-font{
        font-size: 24px;
        font-family: Ubuntu;
    }
    h3{
        font-family: "Ubuntu Thin";
    }
</style>

<form action="./actions/yeniMusteriEkle.php" method="post">
<div class="container register">

    <div class="row">
        <div class="col-md-3">
        <img style="width: 100px;height: 100px;" src="./img/m.png">
            <h3 class="mt-3 " style="color: white;font-family: initial;">Missha <br> Müşteri <br> Kayıt</h3>
        </div>
        <div class="col-md-9 pt-4 pb-4 register-right">
            <div class="row ">
                <div class="col-md-1"></div>
                <div class="col-md-4 label-font">Müşteri isim</div>
                <div class="col-md-7"><input name="cari_ad" type="text" class="form-control"></div>
            </div>
            <div class="row pt-2">
                <div class="col-md-1"></div>
                <div class="col-md-4 label-font">Müşteri Soyisim</div>
                <div class="col-md-7"><input name="cari_soyad" type="text" class="form-control"></div>
            </div>
            <div class="row pt-2">
                <div class="col-md-1"></div>
                <div class="col-md-4 label-font">Müşteri Telefon</div>
                <div class="col-md-7"><input name="cari_telefon" placeholder="5*********" type="text" class="form-control"></div>
            </div>
            <div class="row pt-2">
                <div class="col-md-1"></div>
                <div class="col-md-4 label-font">Müşteri Eposta</div>
                <div class="col-md-7"><input name="cari_eposta" type="text" class="form-control"></div>
            </div>
            <div class="row pt-2">
                <div class="col-md-1"></div>
                <div class="col-md-4 label-font">Müşteri Doğum Tarihi</div>
                <div class="col-md-7"><input type="date" name="cari_dTarih"  class="form-control"></div>
            </div>
            <div class="row pt-2">
                <div class="col-md-1"></div>
                <div class="col-md-4 label-font">İşlem Yapan Personel</div>
                <div class="col-md-7">

                    <select class="form-control" name="cari_personelRef">
                        <?php
                        $sql4 = "SELECT * FROM personeller WHERE personel_magazaRef=".$_SESSION['magaza']." and personel_aktif=1";
                        $result = $mysqli -> query($sql4);
                        $personeller = result_array($result);
                        foreach ($personeller as $k=>$v){
                        ?>
                            <option value="<?=$v['personel_no']?>"><?=$v['personel_adSoyad']?></option>
                        <?php
                        }
                        ?>

                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-2 pt-2 ml-auto"><button class="btn btn-success">Müşteriyi Kaydet</button></div>
    </div>

</div>
</form>