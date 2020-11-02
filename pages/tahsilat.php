
<?php
require_once ('./actions/sepetListele.php');
if(isset($_SESSION["cari_no"]) && intval($_SESSION["cari_no"])>0){
    if(isset($_SESSION["sepet"]["toplam"]) && intval($_SESSION["sepet"]["toplam"])>0){
if(!empty($_POST)){

    foreach ($_POST as $k=>$v){
        if(empty($v)){$v=0;}
        $_SESSION['odeme'][$k]=$v;
    }

    ?>
    <script>
        setTimeout(function () {
            window.location.href="http://localhost/magaza/index.php";
        },2000)
    </script>

        <?php

}
$sql = "SELECT * FROM odemeSecenekleri WHERE os_magazaRef=1 and os_aktif=1";
$result = $mysqli -> query($sql);
$result = result_array($result);


        define("service_url", "https://misshaturkiye.net/Service/request.php");
        require_once ('./core/curl.php');
        $sonuc = curlPost(service_url,array('cari_no'=>$_SESSION["cari_no"],'req'=>'ozelKuponGetir'));
        if($sonuc){
            $kuponOzel = json_decode($sonuc,true);
        }else{
            $kuponOzel = array();
        }
?>


    <style>
        .musteri_div{
            background: #fff;
            border: 1px solid #008ECF;
        }
        body{
            background: #e0e0e0;
        }
        .tableheight{
            min-height: 500px;
        }
        .paymentCard{
            background-color: #498ee4;
            height: 120px;
            border-radius: 6px;
            padding: 20px 30px 0;
            box-sizing: border-box;
            font-size: 14px;
            letter-spacing: 1px;
            font-weight: 300;
            color: white;
        }
        .importantfont
        {
            color: #db2525;

        }

        .odemeLabel{
            background: #031717;
            color: #ffffff;
            font-size: 14px;
        }
        .cekLabel{
            background: #0094C6;
            color: #ffffff;
        }
        .odemeInput{
            height: 100%;
        }
        .odemeTitleimg{
            padding: 4px 1px;
        }
        .odemeTitleSpan{
            padding: 6px 11px;
            font-size: 17px;
            color: #ffffff;
        }
        .odemeBlock{
            background: #ffffff;
        }
        .kuponlarBlock{
            font-size: 14px;
            padding-top: 10px;
        }
        .kuponlarBlock td{
            padding: 3px;
        }
        .cekText{
            color: #E91561;
            white-space: nowrap;
        }
        .smsBtn{
            background: #CBDD52;
            border: 2px solid #4AC2C5;
            border-radius: 6px;
            box-sizing: border-box;
            text-align: center;
            color: #00556C;
        }
        .borderRight{
            border-right: 1px solid;
        }
        .kuponText{
            text-align: center;
            color: #ADADAD;
            margin-top: 37px;
        }
        .kuponInput{
            height: 48px;
        }

    </style>
<div class="container">
<div class="row  mt-5 odemeBlock">

    <div class="col-md-6 ">
        <form action="./index.php?tahsilat=1" method="post">
        <div class="row   text-center ">
            <div class="col-md-12 ">
                <div class="row odemeLabel justify-content-center" style="height: 38px;border-right: 11px solid #fff;">
                    <img  class="img-fluid odemeTitleimg" src="img/t.png"><span class="odemeTitleSpan">Tahsilat</span>
                </div>

                <div class="row mt-2">
                    <div class="table-responsive col-md-12">
                        <table id="sort2" class="grid table table-bordered table-sortable">
                            <thead>
                            <tr><th>Sira</th><th>Ödeme Yöntemi</th><th>Tutar</th></tr>
                            </thead>
                            <tbody>
            <?php
            foreach ($result as  $k=>$v){
            ?>
                <tr>
                    <td data-id="11">1</td>
                    <td><?=$v['os_aciklama']?></td>
                    <td><input name="<?=$v['os_no']."-".$v['os_aciklama']?>" type="text" value="" placeholder="0 TL" class="form-control inp"></td>
                    <td><button class="btn btn-light"><img src="img/del.png"></button></td>
                </tr>
            <? } ?>

            <tr>
                <td data-id="11">1</td>
                <td>Cüzdan</td>
                <td><input name="cüzdan" type="text" value="" placeholder="0 TL" class="form-control inp"></td>
                <td><button class="btn btn-light"><img src="img/del.png"></button></td>
            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="table-responsive col-md-12">
                        <table id="sort4" class="grid table table-bordered table-sortable">
                            <thead>
                            <tr><th>Özet</th><th>Tutar</th></tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Sipariş Tutarı</td>
                                <td id="siparisTutari"><?=$_SESSION['sepet']['toplam']-$_SESSION['sepet']['indirim']?></td>
                            </tr>
                            <tr>
                                <td>Cüzdan Bakiyesi</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>Kupon İndirimi</td>
                                <td>-<span id="kuponIndirim"><?=$_SESSION['kupon']?></span></td>
                            </tr>
                            <tr>
                                <td>Ödenen Tutar</td>
                                <td id="odenenTutar">0</td>
                            </tr>
                            <tr>
                                <td>Kalan Tutar</td>
                                <td id="kalanTutar"><?=$_SESSION['sepet']['toplam']-$_SESSION['sepet']['indirim']-$_SESSION['kupon']?></td>
                            </tr>
                            <tr>
                                <td>Para Üstü</td>
                                <td id="paraUstu">0</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>




             </div>



        </div>
        </form>
    </div>
    <div class="col-md-6 text-center odemeBlock">

                <div class="row odemeLabel justify-content-center" style="border-left: 14px solid #fff;">
                    <img  class="img-fluid odemeTitleimg" src="img/p.png"><span class="odemeTitleSpan">Genel Kuponlar</span>
                </div>
                <div class="row ">
                    <div class="col-md-12" style="background: white">
                        <table class="table kuponlarBlock mt-3">
                            <tbody>
                            <?php
                            $sql ="SELECT * FROM _kampanyalar WHERE kmp_aktif=1 and kmp_kategori=1";
                            $result = $mysqli->query($sql);
                            $kuponlar = result_array($result);
                            foreach ($kuponlar as $k=>$v){ ?>
                                <tr>
                                    <td><?=$v['kmp_kisaAd']?></td>
                                    <td><input type="button" onclick="kuponKullan(<?=$v['kmp_id']?>,this)" value="Kullan" class="sabitKuponlar btn-success"></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-12 " style="background: white" >
                        <button type="button" style="width: 100%" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                            Müşteriye Özel Kuponlar
                        </button>

                    </div>
                    <div class="col-md-12">
                        <div class="text-center mt-2 mb-2"><strong>Veya</strong></div>
                        <div class=""><input class="form-control" placeholder="Kupon Numarası"></div>
                        <div class="mt-1"><input style="width: 100%" type="button" class="btn btn-primary" value="Kullan"></div>
                    </div>

                    <div class="col-md-12 mt-5" >
                        <input type="submit" style="width: 100%" class="btn btn-success" value="Ödeme Ekle" >
                    </div>



        </div>
    </div>

</div>
</div>

        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Kuponlarınız</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="table-responsive col-md-12">
                            <table id="sort8" class="grid table table-bordered table-sortable">
                                <tbody>
                                <?php
                                foreach ($kuponOzel as $k=>$v){
                                    ?>
                                    <tr>
                                        <td><?=$v['mKupon_value']?> TL İndirim </td>
                                        <td><input type="button" onclick="kuponKullan(<?=$v['kmp_id']?>,this)" value="Kullan" class="sabitKuponlar btn-success"></td>
                                    </tr>

                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                    </div>

                </div>
            </div>
        </div>


    <?php }else{
        $_SESSION["msg"] = "Lütfen Ürün Ekleyiniz!";
        header("Location: http://localhost/magaza");
    }
}else{
    $_SESSION["msg"] = "Lütfen  Müşteri Seçiniz!";
    header("Location: http://localhost/magaza");
}
?>


<script>
    
    $(document).on('keyup','.inp',function () {
            let total = 0;
            $.each($('.inp'),function (k,v) {
                if(v.value){
                    total += parseFloat(v.value);
                }
            });
            $('#odenenTutar').html(total)
            let kalan =(parseFloat($('#siparisTutari').text())-parseFloat($('#kuponIndirim').text())-total).toFixed(2);
            let paraustu = 0;
            if(kalan<1){
                paraustu = (kalan*-1).toFixed(2);
                kalan = 0;
            }
            $('#kalanTutar').html(kalan);
            $('#paraUstu').html(paraustu);
    });
    
    function kuponKullan(kmp) {

        if(kmp){
            let formdata = new FormData();
            formdata.set('kupon_id',kmp);
            $.ajax({
                url:'./actions/kuponKullan.php',
                type:'post',
                data:formdata,
                processData: false,
                contentType:false,
                success:function (res) {
                    if(res){
                        window.location.reload();
                    }
                }

            })
        }


    }
</script>

