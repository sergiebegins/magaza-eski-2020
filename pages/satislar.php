<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js"></script>


<div class="container mt-5">
    <table id="example" class="display" cellspacing="0" width="100%">
    <thead>
        <tr>
            <td>fatura-no</td>
            <td>Müsteri</td>
            <td>Tutar</td>
            <td>Tarih</td>
            <td>Online</td>
            <td>Görüntüle</td>
        </tr>
    </thead>
        <tbody>
        <?php
        $sql = "SELECT * FROM faturalar order by fatura_id desc";
        $result = $mysqli -> query($sql);
        $result = result_array($result);
        foreach ($result as  $k=>$v){
        ?>
        <tr>
            <td><?=$v['fatura_faturaNo']?></td>
            <td><?=$v['fatura_full']?></td>
            <td><?=$v['fatura_tutar']?></td>
            <td><?=date('d-m-y h:i:s',$v['fatura_tarih'])?></td>
            <td><?php if($v['fatura_online']){?>
                    <div class="alert alert-success" role="alert">
                        Gönderildi
                    </div>
                <?php }else{ ?>
                    Gönderilmedi
<!--                    <input type="button" onclick="sendOnline(--><?//=$v['fatura_id']?>//)" class="btn btn-primary" value="Merkeze Gönder">
                <?php } ?>
            </td>
            <td><a href="http://localhost/magaza/faturaPdf.php?faturaNo=<?=$v['fatura_id']?>"><input type="button" value="Fatura Detay" class="btn btn-success"></a></td>
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

    function sendOnline(fid) {
        if(fid){
            let formdata = new FormData();
            formdata.set('fatura_id',fid);
            $.ajax({
                url: './actions/faturaGonder.php',
                type:'post',
                data:formdata,
                processData: false,
                contentType:false,
                success:function (res) {
                    if(res){

                    }
                }

            })
        }
    }
</script>