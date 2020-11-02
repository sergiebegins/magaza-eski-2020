<?php
session_start();
require_once ('./core/mysqlConnect.php');
require_once ('./helper.php');
date_default_timezone_set('Europe/Istanbul');

include "./tcpdf/config/lang/eng.php";
include "./tcpdf/tcpdf.php";
$faturaNo =intval($_GET['faturaNo']);

$sql = "SELECT * FROM `faturalar` as f INNER JOIN cariHareketler as ch on f.fatura_id=ch.ch_faturaID INNER JOIN stokHareketler as s on f.fatura_id=s.sh_faturaID INNER JOIN cariler as c on f.fatura_cariRef = c.cari_no WHERE fatura_id=".$faturaNo;

$result = $mysqli -> query($sql);

$fatura = result_array($result);
$fatura = $fatura[0];

$sepet = json_decode($fatura["fatura_sepet"],true);

    $sipNo = $faturaNo;

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, 'cm', PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetMargins(0.8, 3, 0.5,2);
    $pdf->SetHeaderMargin(0);
    $pdf->SetFooterMargin(0);
    $lg = Array();
    $lg['a_meta_charset'] = 'UTF-8';
    $lg['a_meta_language'] = 'tr';
    (string)$bn=date('md').'W'.$sipNo;
    $kim='-MISSHA-';
    $bParams = $pdf->serializeTCPDFtagParameters(array($bn, 'C128', '', '', 5, 1.2, 0.2, array('position'=>'L', 'border'=>false, 'padding'=>0, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>6, 'stretchtext'=>0), 'N'));
    $pdf->setLanguageArray($lg);
    $pdf->SetDisplayMode('fullpage', 'SinglePage', 'UseNone');
    $pdf->addTTFfont('../tcpdf/fonts/VERDANA.TTF', 'TrueTypeUnicode', '', 32,'../tcpdf/fonts/');
    $pdf->SetFont('verdana', '', 5);
    $urunSay =count($sepet['sepet']);
    $sayfaSay=(int)ceil($urunSay / 25);

    $cek =0;//cekgetir
    $gercekGenelToplam =$gercekIndirimliGenelToplam=0;

    $fadres =$fatura["cari_ad"]." ".$fatura["cari_soyad"];


    for($i=0;$i<$sayfaSay;$i++){
        $pdf->AddPage('L');
        $html='<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="34%" height="348" valign="top" >';
        $faturaBody='<table width="93%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="53%" height="54" rowspan="2">&nbsp;</td>
            <td width="47%" height="14" align="right">';
        $faturaBody.=date("d-m-Y h:i:s",time());
        $faturaBody.= '</td>
          </tr>
          <tr>
            <td height="54" valign="top"> '.$fadres.' </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="33">&nbsp;</td>
      </tr>
      <tr>
        <td ><table width="100%" border="0" cellspacing="0" cellpadding="0">';

        if(($sayfaSay>1) && ($i>0)){
            $faturaBody.='<tr>
        <td width="9%" height="10">-----</td>
        <td width="66%" > NAKLİ YEKÜN ----------------</td>
        <td width="4%">--</td>
        <td width="11%">'.round($gercekGenelToplam,2).'</td>
        <td width="10%">-----</td>
        </tr>';
        }
        $toplamKdv=$kdv=0;
        foreach ($sepet['sepet'] as $k=>$v){
            $kdv = ($v['sf']*0.18);
            $kdvSiz = $v['sf']-$kdv;
            $toplamKdv +=  $kdv*$v['adet'];
            $araToplam += ($kdvSiz)*$v['adet'];
            $indirim += $v['indirim'];
            $indirimsizToplam = ($v['sf'])*$v['adet'];
            $toplam += $indirimsizToplam-$v['indirim'];

            $faturaBody.='<tr>
					<td width="9%" height="10">'.$v['urun'].'</td>
					<td width="66%" >'.str_replace('+','',$v['ad']).'</td>
					<td width="4%">'.$v['adet'].'</td>
					<td width="11%">'.round(($kdvSiz),2).'</td>
					<td width="10%">'.round(($kdvSiz)*$v['adet'],2).'</td>
				</tr>';
        }
        $yaziyla=parala($toplam,0);
        $faturaBody.='</table></td>
						  </tr>
						  <tr>
							<td height="23"></td>
						  </tr>
						  <tr>
							<td>';
        if((($i+1)==$sayfaSay)||($sayfaSay<2)){
            $faturaBody.='<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
						  <td colspan="2" rowspan="7" valign="top"> Yalnız : '.$yaziyla.'<br> ';
            $ob=array();
            foreach ($ob as $k=>$v){
                if($v['fiyat'] >0){
                    $faturaBody.=  $v['odemeSekli'].' : '.$v['fiyat'].'<br>';
                }
            }
            $faturaBody.=' SİPARİŞ NUMARASI :'.$sipNo.'<br></td>
						  <td width="22%" align="right" valign="top">Toplam   :</td>
						  <td width="20%"  height="5" align="right" > '.$araToplam.' TL</td>
				  </tr>
				
				 
				  
				<tr>
					<td align="right" valign="top">&nbsp;KDV(18) :</td>
					<td width="20%" height="5" align="right" valign="middle" > '.$toplamKdv.' TL </td>
				</tr>';
            if($cek>0){
                $faturaBody.='<tr>
				  <td align="right" valign="top">Parapuan İndirimi</td>
				  <td height="5" align="right" valign="middle" >'.$cek.' TL</td>
				</tr> ';
            }
            $faturaBody.='<tr>
						  <td align="right" valign="top">İndirim : </td>
						  <td width="20%" height="5" align="right" >'.$indirim.' TL</td>
				</tr>
				<tr>
				  <td align="right" valign="top">&nbsp;KDV Dahil : </td>
				  <td height="5" align="right" valign="middle" >'.$toplam.' TL </td>
				</tr>

        	</table>';
        }
        $faturaBody.='<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr> <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>'.$kim.'</strong><br>
				<tcpdf method="write1DBarcode" params="'.$bParams.'" /></td></tr>
				  </table>';
        $faturaBody.='</td>
					  </tr>
					</table>';
        $html2='</td>
					<td width="35%" valign="top" >'.$faturaBody.'</td>
					<td width="31%" valign="top" >'.$faturaBody.'</td>
				  </tr>
				</table>';
        $html=$html.$faturaBody.$html2;
        $pdf->writeHTML($html, true, false, true, false, '');
    }
    $pdf->lastPage();
    ob_end_clean();
    $pdf->Output('Siparis-'.$sipNo.'.pdf', 'I');

function CekGetir($odeme){
    foreach ($odeme as $k=>$v){
        if($v['odemeSekli'] == "Cek"){
            return $v['fiyat'];
        }
    }
    return 0;
}
function parala($money,$input_format=0){

    $arr1 = array("","Bir","İki","Üç","Dört","Beş","Altı","Yedi","Sekiz","Dokuz");

    $arr10 = array("","On","Yirmi","Otuz","Kırk","Elli","Atmış","Yetmiş","Seksen","Doksan");

    $arr100 = array("","Yüz","İkiYüz","ÜçYüz","DörtYüz","BeşYüz","AltıYüz","YediYüz","SekizYüz","DokuzYüz");

    $add_word = array("","Bin","Milyon","Milyar","Trilyon","Katrilyon","Kentilyon","Seksilyon","Septilyon","Oktilyon");



    if($input_format==0){ //10000.25

        $money=number_format($money,2,',','.');

    }

    else if($input_format==1){ //10,000.25

        $money=str_replace(',','',$money);

        $money=number_format($money,2,',','.');

    }

    else if($input_format==2){ //10000,25

        $money=str_replace(',','.',$money);

        $money=number_format($money,2,',','.');

    }

    else if ($input_format==3){//10.000,25

        $money=$money;

    }

    $money_part1=explode(",",$money);

    $money_part2=explode(".",$money_part1[0]);



    $output='';

    $trees_len=count($money_part2);

    $addword_start=$trees_len-1;

    for($i=0;$i<$trees_len;$i++){

        if(strlen($money_part2[$i]*1)==3){

            $output.=' '.$arr100[substr($money_part2[$i],0,1)].''.$arr10[substr($money_part2[$i],1,1)].''.$arr1[substr($money_part2[$i],2,1)];

        }

        else if(strlen($money_part2[$i]*1)==2){

            $output.=' '.$arr10[substr($money_part2[$i]*1,0,1)].$arr1[substr($money_part2[$i]*1,1,1)];

        }

        else if(strlen($money_part2[$i]*1)==1){

            if(!($addword_start==1 and $money_part2[$i]*1==1)){

                $output.=' '.$arr1[substr($money_part2[$i]*1,0,1)];

            }

        }

        if(($money_part2[$i]*1)>0){

            $output.=' '.$add_word[$addword_start];

        }

        $addword_start=$addword_start-1;

    }

    if(substr($money_part1[1],0,1)==0 and substr($money_part1[1],1,1)==0){

        $output.=' TL';

    }

    else {

        $output.=' TL '.$arr10[substr($money_part1[1],0,1)].$arr1[substr($money_part1[1],1,1)].' Krş';

    }

    return $output;

}