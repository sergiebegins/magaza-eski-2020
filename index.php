<script  src="https://code.jquery.com/jquery-3.5.1.min.js"   integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>



<style>
    /* cyrillic-ext */

    @font-face {
        font-family: 'Ubuntu';
        font-style: normal;
        font-weight: 400;
        src: local('Ubuntu Regular'), local('Ubuntu-Regular'), url(https://fonts.gstatic.com/s/ubuntu/v14/4iCs6KVjbNBYlgoKcg72j00.woff2) format('woff2');
        unicode-range: U+0460-052F, U+1C80-1C88, U+20B4, U+2DE0-2DFF, U+A640-A69F, U+FE2E-FE2F;
    }
    /* cyrillic */
    @font-face {
        font-family: 'Ubuntu';
        font-style: normal;
        font-weight: 400;
        src: local('Ubuntu Regular'), local('Ubuntu-Regular'), url(https://fonts.gstatic.com/s/ubuntu/v14/4iCs6KVjbNBYlgoKew72j00.woff2) format('woff2');
        unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
    }
    /* greek-ext */
    @font-face {
        font-family: 'Ubuntu';
        font-style: normal;
        font-weight: 400;
        src: local('Ubuntu Regular'), local('Ubuntu-Regular'), url(https://fonts.gstatic.com/s/ubuntu/v14/4iCs6KVjbNBYlgoKcw72j00.woff2) format('woff2');
        unicode-range: U+1F00-1FFF;
    }
    /* greek */
    @font-face {
        font-family: 'Ubuntu';
        font-style: normal;
        font-weight: 400;
        src: local('Ubuntu Regular'), local('Ubuntu-Regular'), url(https://fonts.gstatic.com/s/ubuntu/v14/4iCs6KVjbNBYlgoKfA72j00.woff2) format('woff2');
        unicode-range: U+0370-03FF;
    }
    /* latin-ext */
    @font-face {
        font-family: 'Ubuntu';
        font-style: normal;
        font-weight: 400;
        src: local('Ubuntu Regular'), local('Ubuntu-Regular'), url(https://fonts.gstatic.com/s/ubuntu/v14/4iCs6KVjbNBYlgoKcQ72j00.woff2) format('woff2');
        unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    /* latin */
    @font-face {
        font-family: 'Ubuntu';
        font-style: normal;
        font-weight: 400;
        src: local('Ubuntu Regular'), local('Ubuntu-Regular'), url(https://fonts.gstatic.com/s/ubuntu/v14/4iCs6KVjbNBYlgoKfw72.woff2) format('woff2');
        unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    .container-fluid .container{
        font-family: ubuntu;
    }
</style>

<?php

require_once ('./config.php');

if(empty($_GET)){
    require_once ('./actions/sepetListele.php');

    if(empty($sepet)){
        $sepet = array(
            'toplam'=>0,
            'indirim'=>0
        );
    }
?>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/dataTables.material.min.css"></script>

<style>
    .header{
        background: linear-gradient(90.15deg, #008ECE 28.44%, #00B19E 92.15%);
        min-height: 9%;
    }

    .border-grey{
        border: 1px solid #ADADAD;
        height: 500px;
    }
    .menu-font{
        color: #fff;
        font-size: 10px;
    }
    .rectangle1{
        background: #007AB3;
    }
    .white{
        color: #fff;
    }
    .bg-white{
        background: #fff;
    }
    .border-icon{
        border: solid 1px #4AC2C5;
        border-radius: 6px;
        padding: 11px 15px;
        width: 58px;
        margin-left: auto;
        margin-right: auto;
    }
    span {
        font-size: 14px;
        text-align: center;
    }
    .col-md-menu{
        flex: 0 0 12.333333%;
        max-width: 12.333333%;
        text-align: center;
    }
    .img-fluid{
        min-height: 33px;
    }
    input::placeholder {
        color: #AFAFAF !important;
        font-size: 11px;
    }
    .ms-bg{
      background:   #4AC2C5;
    }
    .left-label{
        left: 2px;
        z-index: 1;
    }
    .col-md-ara{
        flex: 0 0 2.333333%;
        max-width: 2.333333%;
    }
    .col-md-sag{
        flex: 0 0 64.333333%;
        max-width: 64.333333%;
    }
    .borkodtext{
        background: #E8ECEF;
    }
    .btn-missha{
        background: #4AC2C5;
        font-size: 11px;
        color: #ffffff;
    }
    .mt2rem{
        margin-top: 1.625rem;
    }
    .img-border{
        background: #000;
        border-radius: 23px;
        padding: 5px;
    }
    .alt-cizgi{
        border-bottom: 1px solid #F4F2FF;
    }
    .fktitle{
        color: #4AC2C5;
        border-bottom: 2px solid #E6E3F5;
    }
    .stitle{
        color: #008ECF;
        border-bottom: 2px solid #E6E3F5;
    }
    .fkbg{
        background: #FAF9FF;
    }
    .itutar{
        color: #E91561;
    }

</style>

<div class="container-fluid ">
    <div class="row header ">
        <div class="col-md-1 rectangle1">
            <div class="row justify-content-center mt-4">
            <img class="img-fluid" src="img/r.png">

            </div>
            <div class="row justify-content-center mt-1">
                <span class="menu-font">Beklemeye Al</span>
            </div>
        </div>
        <div class="col-md-ara pl-2 text-center mt-4"><img class="img-border" src="img/m.png"></div>
        <div class="col-md-2 pl-4 pt-1  mt2rem white"><?=empty($_SESSION["cari_ad"])?'':$_SESSION['cari_ad']?></div>
        <div class="col-md-1"></div>
        <div class="col-md-7 pl-0">
            <div class="row">
                <div class="col-md-menu  ml-auto mt-2 white pl-0 pr-1 "><div onclick="menu('satislar')" class="border-icon"><img class="img-fluid" src="img/s.png"></div><span>Satişlar </span></div>
                <div class="col-md-menu mt-2 white pl-0 pr-1"><div onclick="menu('yeniMusteri')" class="border-icon"><img class="img-fluid" src="img/y.png"></div><span>Yeni Müşteri </span></div>
                <div class="col-md-menu mt-2 white pl-0 pr-1"><div onclick="menu('kampanyalar')" class="border-icon"><img class="img-fluid" src="img/k.png"></div><span>Kampanyalar </span></div>
                <div class="col-md-menu mt-2 white pl-0 pr-1"><div onclick="menu('tahsilat')" class="border-icon"><img class="img-fluid" src="img/t.png"></div><span>Tahsilat </span></div>
                <div class="col-md-menu mt-2 white pl-0 pr-1"><div onclick="menu('iade')" class="border-icon"><img class="img-fluid" src="img/i.png"></div><span>İade İşlemi </span></div>
                <div class="col-md-menu mt-2 white pl-0 pr-1"><div onclick="menu('iptal')" class="border-icon"><img class="img-fluid" src="img/fi.png"></div><span>Fatura İptal </span></div>
                <div class="col-md-menu mt-2 white pl-0 pr-1"><div onclick="menu('fatura')" class="border-icon"><img class="img-fluid" src="img/fk.png"></div><span>Fatura Kes </span></div>
            </div>
        </div>
    </div>
    <div class="row mt-3 ">
        <div class="col-md-4  border-grey">
            <div class="row mt-3">
                <div class="col-md-2 ms-bg left-label ml-3">
                    <img src="img/ms.png" class="img-fluid mt-1">
                </div>
                <div class="col-md-9 pl-0 pr-0">
                    <input type="text" onkeyup="cariSec(event,this)" class="form-control" placeholder="| Müşteri Seçiniz ( Telefon ya da E Posta yazarak aratınız..)">
                </div>
                <div class="container-fluid  mt-3">
                    <div class="row mt-2 fkbg ">
                        <div class="col-md-12 fktitle">Faydalanılan Kampanyalar: </div>
                        <?php if(!empty($kampanya)){
                         foreach ($kampanya as $k=>$v){
                            if($v['kmp_hakedis']>0){
                                ?>
                                <div class="col-md-6 pt-3 alt-cizgi"><?=$v['kmp_kisaAd']?>: </div>
                                <div class="col-md-6 pt-3 alt-cizgi"><?=round($v['kmp_hakedis'],2)?> TL </div>
                            <?php }}} ?>
                    </div>
                    <div class="row mt-4">
                        <?php
                        if(empty($_SESSION['fatura_no'])){
                            $sql ="SELECT sira FROM sayac WHERE tip=2";
                            $result = $mysqli->query($sql);
                            $result = result_array($result);
                            $faturaNo = $result[0]['sira'];
                            $_SESSION['fatura_no'] = "A-".$faturaNo;
                        }
                        ?>
                        <div class="col-md-6 alt-cizgi">Fatura Seri No: </div><div class="col-md-6"><input class="form-control" value="<?= $_SESSION['fatura_no']?>" readonly></div>
                        <div class="col-md-12 pt-2 stitle">Satış Özeti:</div>
                        <div class="col-md-9 pt-2 alt-cizgi itutar">İndirim:</div>
                        <div class="col-md-3 pt-2"><?=empty($sepet['indirim'])?0:$sepet['indirim']?> TL</div>
                        <div class="col-md-9 pt-2 alt-cizgi"><strong>Toplam:</strong></div>
                        <div class="col-md-3 pt-2"><?=$sepet['toplam']-$sepet['indirim']?></div>

                        <?php if(!empty($_SESSION['odeme'])){?>
                            <div class="col-md-12 pt-2 stitle">Ödeme:</div>
                            <?php
                            $toplamOdeme = 0;
                            foreach ($_SESSION['odeme'] as $k=>$v){
                                if($v>0){
                                    $toplamOdeme += $v;
                            ?>
                            <div class="col-md-9 pt-2 alt-cizgi"><strong><?=substr($k,strrpos($k,"-")+1)?></strong></div>
                            <div class="col-md-3 pt-2"><?=$v?></div>
                            <?php
                             }}
                            $_SESSION['odeme_toplam'] = $toplamOdeme;
                        }
                        ?>


                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-ara"></div>
        <div class="col-md-sag  border-grey">
            <div class="row pl-3 pr-3 mt-3">
                <div class="col-md-2  borkodtext left-label text-center pt-2">Barkod:</div>
                <div class="col-md-8 pl-0"><input onkeyup="sepetEkle(event,this)" type="text" class="form-control" placeholder="Ürün Barkodu Okutunuz!"></div>
                <div class="col-md-2 pl-1 pr-0"><button  onclick="menu('tumUrunler')" class="btn btn-missha"><img src="img/l.png">Listeden Seçiniz</button></div>

                <div class="col-md-12 mt-3">
                    <table id="table" class="display">
                        <thead>
                        <tr>
                            <th>Ürün Adı</th>
                            <th>Adet</th>
                            <th>Fiyatı</th>
                            <th>İndirimli</th>
                            <th>sil</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($sepet['sepet'])){
                           $_SESSION['sepet'] = $sepet;
                       foreach ($sepet['sepet'] as $k=>$v){ ?>
                        <tr>
                            <td><?=$v["ad"]?>(<?=$v['stok']?>)</td>
                            <td><?=$v['adet']?></td>
                            <td><?=round(($v['sf']*$v['adet']),2)?></td>
                            <td><?=round((($v['sf']*$v['adet'])-$v['indirim']),2)?></td>
                            <td><span onclick="sepetSil(<?=$v['urun']?>)"><img src="img/del.png"></span></td>
                        </tr>
                        <?php }  } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

    <div id="messageModal" class="modal" tabindex="-1" role="dialog" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bildirim Mesajı</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><?=$_SESSION["msg"]?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tamam</button>
                </div>
            </div>
        </div>
    </div>




<script>
    $(document).ready(function() {
        $('#table').dataTable({
            "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Turkish.json"
        }
        });

        <?php

        if(!empty($_SESSION["msg"])){
          ?>
        $('#messageModal').modal('toggle');
        <?php
        $_SESSION["msg"] = null;
        }
        ?>

    } );
    function menu(key) {
        window.location.href='index.php?'+key+'=1';
    }




    function sepetSil(urun){
        if(urun){
            $.ajax({
                url:'./actions/sepetSil.php?urun='+urun,
                type:'post',
                success:function (res){
                    if(res){
                        let jsn = JSON.parse(res);
                        if(jsn.status == 1){
                            window.location.reload();
                        }
                    }
                }
            })
        }
    }
    function cariSec(e,t){
        let key = e.keyCode ? e.keyCode : e.charCode;
        let cari = $(t).val();
        if(key == 13){
            $.ajax({
                url: "./actions/cariSec.php?cari_inp="+cari,
                type: 'post',
                success: function(res){
                    if(res){
                        let jsn = JSON.parse(res);
                        if(jsn.status == 1){
                            window.location.reload();
                        }
                    }

                }
            });
        }
    }

    function sepetEkle(e,t){
        let key = e.keyCode ? e.keyCode : e.charCode;
        let urun = $(t).val();
        if(urun){
            if(key == 13){
                $.ajax({
                    url: "./actions/sepeteEkle.php?barkod="+urun,
                    type: 'post',
                    success: function(res){
                        if(res){
                            let jsn = JSON.parse(res);
                            if(jsn.status == 1){
                                window.location.reload();
                            }
                        }
                    }
                });
            }
        }

    }
</script>




<?php

}else{
    $key = array_keys($_GET);
    require_once ('./pages/'.$key[0].'.php');
}

?>