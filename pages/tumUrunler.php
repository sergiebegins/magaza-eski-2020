<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>


<div class="container mt-5">
    <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Ürün Adı</th>
            <th>Kodu</th>
            <th>Barkodu</th>
            <th>Barkodu2</th>
            <th>Fiyatı</th>
            <th>kampanya1</th>
            <th>kampanya2</th>
            <th>kampanya3</th>
        </tr>
        </thead>
        <tbody>

            <?php
            $sql = "SELECT u.urun_no,u.urun_ad,u.urun_kod,u.urun_barkod,urun_barkod2,
                    s.stok_satisFiyat,s.stok_uKRef,s.stok_sKRef,s.stok_kHRef FROM urunler u 
                    INNER JOIN stoklar s on s.stok_urunRef=u.urun_no 
                    WHERE s.stok_magazaRef=1 and urun_aktif=1 ORDER BY `urun_no` DESC";
            $result = $mysqli -> query($sql);
            $result = result_array($result);

            $sql2 = "SELECT * FROM _kampanyalar";
            $result2 = $mysqli -> query($sql2);
            $kampanyalar = result_array($result2);
            foreach ($kampanyalar as $k=>$v){
                $kmp[$v['kmp_id']] = $v['kmp_kisaAd'];
            }
            $kmp[0]="Yok";
            $kampanyalar=$result2=$sql=$sql2=null;

            foreach ($result as $k=>$v){
            ?>
       <tr>
            <td><?=$v['urun_ad']?></td>
            <td><?=$v['urun_kod']?></td>
            <td><?=$v['urun_barkod']?></td>
            <td><?=$v['urun_barkod2']?></td>
            <td><?=$v['stok_satisFiyat']?></td>
            <td><?=$kmp[$v['stok_uKRef']]?></td>
            <td><?=$kmp[$v['stok_sKRef']]?></td>
            <td><?=$kmp[$v['stok_kHRef']]?></td>


        </tr>
        <?php  } ?>
        </tbody>
    </table>
</div>


<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Turkish.json"
            }
        });
    } );
</script>