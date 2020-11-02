
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>
<div class="container mt-5">
    <table id="example" class="display" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Kampanya</th>
            <th>İndirim Miktar</th>
            <th>Minimum Tutar</th>
            <th>Minimum Adet</th>
            <th>indirim Şekli</th>
            <th>Başlangıç</th>
            <th>Bitiş</th>
        </tr>
        </thead>
        <tbody>
        <?php
            $sql = "SELECT * FROM _kampanyalar WHERE kmp_aktif=1 and kmp_kategori=0";

            $result = $mysqli -> query($sql);
            $result = result_array($result);
            foreach ($result as  $k=>$v){
        ?>
        <tr>
            <td><?=$v['kmp_adi']?></td>
            <td><?=$v['kmp_indirimMiktar']?></td>
            <td><?=$v['kmp_minTutar']?></td>
            <td><?=$v['kmp_minAdet']?></td>
            <td><?=$v['kmp_indirimSekli']?></td>
            <td><?=$v['kmp_baslangicTarih']?></td>
            <td><?=$v['kmp_bitisTarih']?></td>

        </tr>
        <?php } ?>
        </tbody>
    </table>
</div>


<script>
    $(document).ready(function() {
        $('#example').DataTable({
            "pageLength": 25,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Turkish.json"
            }
        });
    } );
</script>