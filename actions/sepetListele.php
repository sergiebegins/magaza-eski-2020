<?php
require_once ('./core/mysqlConnect.php');
require_once ('./helper.php');

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);


if(isset($_SESSION["cari_no"]) && intval($_SESSION["cari_no"])>0){
    $cari_no =intval($_SESSION["cari_no"]);
    $sql = "SELECT * FROM temp WHERE temp_cari = ".$cari_no;
    $result = $mysqli -> query($sql);
    $temp = result_array($result);

    $result = null;
if(!empty($temp)){
    $sql2 = "SELECT * FROM _kampanyalar";
    $result = $mysqli -> query($sql2);
    $kampanyalar = result_array($result);
    $result = null;

    foreach ($temp as $k=>$v){
        $sepetUrunler[] = $v['temp_urun'];
    }



    $sql3 = "SELECT * FROM urunler u inner JOIN stoklar s on s.stok_urunRef = u.urun_no WHERE u.urun_no IN(".implode(',',$sepetUrunler).") 
and stok_magazaRef=".$_SESSION['magaza'];
    $result = $mysqli -> query($sql3);
    $urunler = result_array($result);


    $sql4 = "SELECT * FROM tempKupon INNER JOIN _kampanyalar on tempKupon.kupon_id = _kampanyalar.kmp_id WHERE tempKupon.cari_no = ".$cari_no;
    $result4 = $mysqli -> query($sql4);
    $kupon = result_array($result4);


    $sepetim = new Basket();
    $sepetim->kampanya = $kampanyalar;
    $sepetim->users = $cari_no;
    $sepetim->sepet = $temp;
    if($kupon){
        $sepetim->basket['kupon'] = $kupon[0];
    }
    $sepetim->urunSonuc = $urunler;
    $sepet =  $sepetim->index();


    $kampanya = $sepetim->kampanya ;
    if($sepetim->basket['kuponIndirim']){
        $_SESSION['kupon'] = $sepetim->basket['kuponIndirim'];
    }

}


}




class Basket
{

    public $kampanya;
    public $users;
    public $basket;
    public $sepet;
    public $urunSonuc;
    public function __construct()
    {

        $this->products=array(); //sepetteki ürün bilgileri
        $this->kampanyaDisiTutar=0;//kampanyası olmayan veya şartlar gereği kampanyadan faydalanamamamış ürünlerin tutarı
        $this->firsatUrunleri=array();

    }


    public function getProducts(){


        foreach ($this->sepet as $v){
            $this->basket['sepet'][$v['temp_urun']] = array(
                'urun'=>$v['temp_urun'],
                'adet'=>$v['temp_adet'],
                'tarih'=>time()
            );
        }

        foreach ($this->kampanya as $v){
            $kampanyalar[$v['kmp_id']] =$v;
            $kampanyalar[$v['kmp_id']]['kmp_hakedis'] = 0;
        }


        $this->kampanya = $kampanyalar;



        foreach($this->urunSonuc as $s){


            $this->products[$s['urun_no']]=$s;
            if(!empty( $kampanyalar[$s['stok_uKRef']])){
                $s['stok_uKRefTip'] =  intval($kampanyalar[$s['stok_uKRef']]["kmp_tip"]);
            }else{$s['stok_uKRefTip'] =0;}


            $this->basket['sepet'][$s['urun_no']]=array_merge($this->basket['sepet'][$s['urun_no']],array(
                'sf'=>$s['stok_satisFiyat'],
                'ad'=>$s['urun_ad'],
                'tr'=>$s['urun_tr'],
                'stok'=>$s['urun_miktar'],
                'kmp'=>$s['stok_uKRef'],
                'sKmp'=>$s['stok_sKRef'],
                'hKmp'=>$s['stok_kHRef'],
                'kmpTip'=>$s['stok_uKRefTip'],
                'indirim'=>0
            ));

//            if(($s['stok_uKRef']<=0 || $s['stok_uKRefTip']== 1) && $s['stok_sKRef']<=0 && $s['stok_kHRef']<=0){
//                $this->kampanyaDisiTutar+=$this->basket['sepet'][$s['urun_no']]['adet'] * $s['stok_satisFiyat'];
//            }
        }
        $sonuc=null;
    }
    public function index()
    {

        $this->getProducts();

        $this->basketRender();

       $this->kampanya=$this->valueSort($this->kampanya,'kmp_hakedis',SORT_DESC);


        return $this->basket;

    }
    public function basketRender(){


        $urunGrup=array();//kampanyasına göre gruplanmış olarak ürünleri aldığımız geçici dizi
        $gecerliKampanya=0;//döngünün geçerli kampanyası
        $kampanyaTipi=$kmpID=$adet=$oltaSay=$sepetToplami=0;
        $kampanyaFunction='kampanyaHesapla_';
        $kampanyaSay=$kampanyaID=$kampanyaIDs=$kampanyaIDh=0;
        $oltalar=$baliklar=$sepetExtra=array();

        $this->basket['sepet']= $this->Multisort_Array($this->basket['sepet'],'kmpTip','kmp');
        $this->basket['toplam']=$this->basket['indirim']=0;




        foreach ( $this->basket['sepet'] as $basketItem ) {


            $sepetToplami += floatval($basketItem['adet'] * $basketItem['sf']);

            if(empty($this->basket['kampanyaBloke']) || $this->basket['kampanyaBloke']==0){
                $kampanyaID = intval($basketItem['kmp']);
                $kampanyaIDs = intval($basketItem['sKmp']);//sepet extra
                $kampanyaIDh = intval($basketItem['hKmp']);//balık...


                if ($kampanyaID > 0) {

                    $kampanyaTipi = $basketItem['kmpTip'];


                    if ($kampanyaTipi == 1) {
                        $oltalar[] = $kampanyaID;
                    }


                    if ($kampanyaID == $gecerliKampanya) {
                        $urunGrup[] = $basketItem;
                        $adet += $basketItem['adet'];
                    } else {
                        if ($adet > 0) {// ürün grup doluysa gönder...
                            $this->{$kampanyaFunction}($urunGrup, $kmpID, $adet, $baliklar);
                        }
                        $urunGrup = array();//ürün grup yeni değerlerle yeniden oluştur
                        $adet = 0;
                        $urunGrup[] = $basketItem;
                        $adet += $basketItem['adet'];
                    }

                    $gecerliKampanya = $kampanyaID;

                    $kampanyaFunction = 'kampanyaHesapla_' . $kampanyaTipi;
                    $kmpID = $kampanyaID;


                } elseif ($kampanyaIDh > 0) {                                                                                       //

                    $basketItem['oltaRef'] = $kampanyaIDh;                                                                    //
                    $baliklar[] = $basketItem;


                }                                                                                                           //

                if ($kampanyaIDs > 0) {

                    $sepetExtra[] = $basketItem;


                }

                if ($kampanyaID <= 0 && $kampanyaIDh <= 0 && $kampanyaIDs <= 0) {

                    $this->kampanyaDisiTutar += $basketItem['sf'] * $basketItem['adet'];

                }


            }
        }




        if(count($urunGrup)>0){ //eğer döngüden artan kampanyalı ürün varsa gönder..
            $this->{$kampanyaFunction}($urunGrup,$kmpID,$adet,$baliklar);
            $urunGrup=$kampanyaTipi=$kampanyaID=null;
            $adet=0;
        }



        if(count($sepetExtra) >0){ //eğer sepet extra indirimi olan ürünler varsa gönder

            $this->kampanyaHesapla_7($sepetExtra);
            $sepetExtra=null;

        }



        if(count($oltalar) <= 0 && count($baliklar) > 0){ // eğer sepette balık var ve olta yok ise kampanyadisi tutarları hesapla (çünkü olta yoksa kampanya hesaplaması yok...)

            foreach($baliklar as $balik){

                $this->kampanyaDisiTutar+=$balik['adet']*$balik['sf'];

            }

        }



        $this->basket['toplam']=$sepetToplami;
        $this->sepetKampanya();



        if(!empty($this->basket['kupon'])){

            $this->kuponHesapla();

        }



    }
    public function kampanyaHesapla_1( ...$params){
        /* X Alana Y İndirimli Kampanyası
        Kampanyaya tüm oltalar ve tüm balıklar gönderilir.
        öncelikle oltalar elden geçirilir be kampanya idsine göre kaç adet olduğu belirlenir.
        sonra balıklar kontol edilir ve oltası mevcut ise adet kontrolünden geçirelerek işlenir. (1 olta bir balık yakalar, varsa 2.balık boşta kalır...)
        boşta kalan balıkların tutarları kampanyadisitutara eklenir.
        */

        $oAdet=$indirim=$kampanyaHakedis=0;
        $oltalar=$adetler=array();
        if(!empty($params[3])){
            $params[3]=$this->Multisort_Array($params[3],'sf','adet',SORT_DESC,SORT_ASC); // Balıkları fiyatlarına göre sıralayalım
        }



        foreach($params[0] as $o){
            if(!in_array($o['kmp'],$oltalar)){
                $oltalar[]=$o['kmp'];
                $adetler[$o['kmp']]=0;
            }
            $adetler[$o['kmp']]+=$o['adet'];

        }




        foreach($params[3] as $b) {
            $hakedis=$urunIndirim=0;
            $indirim = $this->basket['sepet'][$b['urun']]['indirim'];
            if ( in_array($b['oltaRef'],$oltalar) ) {

                if($b['adet'] <= $adetler[$b['oltaRef']]){

                    $urunIndirim=$b['adet']*(($b['sf'] * $this->kampanya[$b['oltaRef']]['kmp_indirimMiktar'] ) / 100);
                    $adetler[$b['oltaRef']]-=$b['adet'];

                }else{
                    $urunIndirim=$adetler[$b['oltaRef']]*(($b['sf'] * $this->kampanya[$b['oltaRef']]['kmp_indirimMiktar'] ) / 100);
                    $this->kampanyaDisiTutar+=$b['sf']*($b['adet']-$adetler[$b['oltaRef']]);
                    $adetler[$b['oltaRef']]=0;
                }

                $this->kampanya[$b['oltaRef']]['kmp_hakedis']+=$urunIndirim;
                $kampanyaHakedis+=$urunIndirim;
                $this->basket['sepet'][$b['urun']]['indirim'] = $indirim+$urunIndirim;

            }else{

                $this->kampanyaDisiTutar+=$b['sf']*$b['adet'];

            }

        }

        $this->basket['indirim']+=$kampanyaHakedis;



    }
    public function kampanyaHesapla_2(...$params){

        /*
         * X al Y öde Kampanyası
         * Bu modülde kampanyaya dahil ürünler toplanır ve kampanya şartındaki minimum adet miktarına göre mod alınır ve dışarda kalacak olan en yüksek fiyatlı ürün(ler) dışarda bırakılır
         * sonrasında indirim olarak hesaplanacak en ucuz ürünler toplanarak indirim miktarı hesaplanır ve kampanyadan etkiklenen tüm ürünlere indirim dağıtılır.
         * son olarak kampanya dışı kalan ürünlerin tutarları kampanyadisitutara eklenir.
         *
         * */
        $arr=$this->Multisort_ArrayNotKey($params[0],'sf','adet');//fiyatına ve ve sepetteki adetine göre sıralayalım
        $toplamAdet=$params[2];//sepette bu kampanyada kaç ürün mevcut
        $toplamUrun=count($arr);//sepette bu kampanyada kaç ürün mevcut
        $indirimAdet=intval($toplamAdet/$this->kampanya[$params[1]]['kmp_minAdet']);//kaç bedava  3
        $indirimTutar=$hesaplananIndirimAdet=$kampanyaliToplamTutar=0;
        $hesapAdet=$indirimAdet*$this->kampanya[$params[1]]['kmp_minAdet'];//9
        $x=$hesapAdet;
        for($i=0;$i<$toplamUrun;$i++){

            if($arr[$i]['adet'] <= $hesapAdet){

                $kampanyaliToplamTutar+=$arr[$i]['adet']*$arr[$i]['sf'];
                $hesapAdet-=$arr[$i]['adet'];

            }else{

                $kampanyaliToplamTutar+=$hesapAdet*$arr[$i]['sf'];
                $this->kampanyaDisiTutar+=$arr[$i]['sf']*($arr[$i]['adet']-$hesapAdet);
                $hesapAdet=0;
            }
            if($hesaplananIndirimAdet < $indirimAdet){

                $hesaplananIndirimAdet++;
                $indirimTutar+=$arr[$i]['sf'];

            }

        }
        $hesapAdet=$x;
        $this->kampanya[$params[1]]['kmp_hakedis']+=$indirimTutar;
        $this->basket['indirim']+=$indirimTutar;



        if($indirimAdet >0 && $indirimTutar >0 ){

            foreach ($arr as $u){
                $gecmisIndirim=$this->basket['sepet'][$u['urun']]['indirim'];

                if($u['adet']<= $hesapAdet){

                    $this->basket['sepet'][$u['urun']]['indirim']+=$gecmisIndirim+((($u['sf']-$gecmisIndirim)*$indirimTutar/$kampanyaliToplamTutar*$u['adet']));
                    $hesapAdet-=$u['adet'];

                }else{

                    $this->basket['sepet'][$u['urun']]['indirim']+=(($gecmisIndirim+($u['sf']-$gecmisIndirim)*$indirimTutar/$kampanyaliToplamTutar)*$hesapAdet);
                    $hesapAdet=0;
                }

            }

        }



    }
    public function kampanyaHesapla_3(...$params){

        /*
         * İkincisine İndirim Kampanyası
         * Bu modülde kampanyadaki ürünler toplanır gerekiyorsa en yüksek fiyatlısı dışarda bırakılır
         * Önce kampanyadan etkilenen ürünlerin toplam tutarı,
         * sonra ise indirim uygulanacak ürünlerin indirim tutarları hesaplanır.
         * hesaplanan indirim tutarı kampanyadan etkilenen ürünlerin tamamına dağıtılır.
         * kampanya dışı kalan ürünlerin tutarı kampanyadisitutara eklenir
         * */



        $arr=$this->Multisort_ArrayNotKey($params[0],'sf','adet',SORT_ASC,SORT_DESC);//fiyatına ve ve sepetteki adetine göre sıralayalım

        $toplamAdet=intval($params[2]/2)*2;//sepette bu kampanyada kaç ürün mevcut
        $toplamUrun=count($arr);
        $indirimAdet=$toplamAdet/2;//kaçtanesi indirimli

        $indirimTutar=$hesaplananIndirimAdet=$kacOldu=$araIndirim=$say=$kampanyaliToplamTutar=$kacOldu2=0;

        $kampanyaliToplamTutar=0;
        $urunIndirim=0;
        $sayIndirim=0;

        if($indirimAdet >0){


            for($i=0;$i<$toplamUrun;$i++){

                $urunIndirim=$this->basket['sepet'][$arr[$i]['urun']]['indirim'];


                if($arr[$i]['adet']  <= ($toplamAdet-$say) ){
                    $kampanyaliToplamTutar+=($arr[$i]['adet']*$arr[$i]['sf'])-$urunIndirim;
                    $say+=$arr[$i]['adet'];


                }elseif ($arr[$i]['adet']  > ($toplamAdet - $say) ){
                    $kampanyaliToplamTutar+=(($toplamAdet-$say)*$arr[$i]['sf'])-$urunIndirim;
                    $this->kampanyaDisiTutar+=$arr[$i]['sf'];// artarsa 1 adet artacak...

                    $say+=$toplamAdet-$say;


                }


                if( $sayIndirim < $indirimAdet && $arr[$i]['adet'] <= $indirimAdet-$sayIndirim ){
                    $indirimTutar+=$arr[$i]['adet']*($arr[$i]['sf']-$urunIndirim)*$this->kampanya[$params[1]]['kmp_indirimMiktar']/100;
                    $sayIndirim+=$arr[$i]['adet'];

                }elseif($sayIndirim < $indirimAdet && $arr[$i]['adet'] > $indirimAdet-$sayIndirim){

                    $indirimTutar+=($indirimAdet-$sayIndirim)*($arr[$i]['sf']-$urunIndirim)*$this->kampanya[$params[1]]['kmp_indirimMiktar']/100;
                    $sayIndirim+=($indirimAdet-$sayIndirim);
                }



            }


        }

        // kampanya ve sepete indirim tutarını yansıtıyoruz..
        $this->kampanya[$params[1]]['kmp_hakedis']=$indirimTutar;
        $this->basket['indirim']+=$indirimTutar;
        $dagit=0;


        //sepetteki kampanyadan etkilenen ürünlerin birim indirimlerini hesaplıyoruz.
        foreach ($arr as $u){
            if($hesaplananIndirimAdet < $indirimAdet){
                $urunIndirim=$this->basket['sepet'][$u['urun']]['indirim'];

                $dagit=(($u['sf']-$urunIndirim))*$indirimTutar/$kampanyaliToplamTutar;

                $this->basket['sepet'][$u['urun']]['indirim']=$urunIndirim+($dagit*$u['adet']);

            }


        }



    }
    public function kampanyaHesapla_4(...$params){



    }
    public function kampanyaHesapla_5(...$params){

        /*Minimum tutar  şartsız direkt indirim kampanyası
Bu modülde direkt ve yüksek oranda indirim sözkonusudur bu nedenle bu ürünler ürün bazında kamapanya olarak yalnızca bu kampanyadan faydalanabilir.
        */


        $kampanyaIndirim=0;

        foreach ($params[0] as $p){

            $hakedis=(($this->products[$p['urun']]['stok_satisFiyat'] * $p['adet'])  * $this->kampanya[$params[1]]['kmp_indirimMiktar']) / 100;
            $this->basket['sepet'][$p['urun']]['indirim'] = $hakedis;
            $kampanyaIndirim+=$hakedis;

        }

        if($kampanyaIndirim >0){
            $this->kampanya[$params[1]]['kmp_hakedis']+=$kampanyaIndirim;
            $this->basket['indirim']+=$kampanyaIndirim;
        }


    }
    public function kampanyaHesapla_6(...$params){

        /*Minimum tutar Şartlı direkt indirim kampanyası
        Bu modülde, eğer kampanyada minimum tutar şartı var ise kampanyadisi tutarla kıyaslanır ve gerekli tutar yakalanmışsa indirim uygulanır ve
        kampanyadisitutar  - kampanyanın minimum tutarı nisbetinde azaltılır.
        */
        $kampanyaIndirim=0;

        foreach ($params[0] as $p){

            $indirim = $this->basket['sepet'][$p['urun']]['indirim'];
            if ($this->kampanyaDisiTutar >= $p['adet'] * $this->kampanya[$params[1]]['kmp_minTutar']) {
                $hakedis=((($this->products[$p['urun']]['stok_satisFiyat'] * $p['adet']) - $indirim) * $this->kampanya[$params[1]]['kmp_indirimMiktar']) / 100;
                $this->kampanyaDisiTutar -= $p['adet'] * $this->kampanya[$params[1]]['kmp_minTutar'];
                $this->basket['sepet'][$p['urun']]['indirim'] = $indirim+$hakedis;
                $kampanyaIndirim+=$hakedis;
            } else {
                for ($x = $p['adet']; $x > 0; $x--) {
                    if ($this->kampanyaDisiTutar >= $this->kampanya[$params[1]]['kmp_minTutar']) {
                        $hakedis=(($this->products[$p['urun']]['stok_satisFiyat'] - $indirim) * $this->kampanya[$params[1]]['kmp_indirimMiktar']) / 100;
                        $this->kampanyaDisiTutar -= $this->kampanya[$params[1]]['kmp_minTutar'];
                        $this->basket['sepet'][$p['urun']]['indirim'] = $indirim+$hakedis;
                        $kampanyaIndirim+=$hakedis;
                    }
                }
            }


        }



        if($kampanyaIndirim >0){
            $this->kampanya[$params[1]]['kmp_hakedis']+=$kampanyaIndirim;
            $this->basket['indirim']+=$kampanyaIndirim;
        }

    }
    public function kampanyaHesapla_7(...$params){
        /*
         Ürüne Sepette Extra indirim
         Bu Fonksiyon ürün kampanyaları arasında en son çalışan fonksiyondur...
        sepette extra indirim uygulanan ürünlerin tamamı tek seferde gelir ve dahil olduğu kampanya şartları uygulanır.
        istisnai durum -> Sepette extra indirim uygulanan ürün fırsat ürünleri kampanyasında olmamalıdır.

        */



        $kampanyaHakedis=0;
        foreach($params[0] as $p){

            if(!in_array($p['urun'],$this->firsatUrunleri)){

                $urunIndirim = $this->basket['sepet'][$p['urun']]['indirim'];
                $hakedis=(($p['sf']-$urunIndirim)  * $this->kampanya[$p['sKmp']]['kmp_indirimMiktar'])/100;
                $this->basket['sepet'][$p['urun']]['indirim']=$urunIndirim+$hakedis;
                $this->kampanya[$p['sKmp']]['kmp_hakedis']+=$hakedis;
                $kampanyaHakedis+=$hakedis;

            }
        }

        $this->basket['indirim']+=$kampanyaHakedis;






    }
    public function kampanyaHesapla_8(...$params){


    }
    public function sepetKampanya(...$params){
        /*
         * Bu modül Tüm kampanyalar hesaplandıktan sonra çalışır , böylece sepetin elde edeceği en son tutarı baz alarak var ise sepet kampanyası uygular.
         * sepet kampanyası olupta farklı minimum tutar belirlenen kampanyalar için (örn. 150 tl üserine 10tl 250 tl üzerine 25tl 750 tl üzerine 85tl indirim)
         * sepet tutarı hangi aralığa girdiği test edilerek işleme alınır. nihayetinde sepete tek bir kampanya uygulanabilir.
         * eğer sepet kampanyasından bir indirim oluşursa bu tutar tüm ürünlere orantısal olarak  dağıtılır.
         * */


        $sepetKampanyalari=array(8,9,10);
        $sepetToplami=$this->basket['toplam']-$this->basket['indirim'];







        $tuttuKiyas=0;
        $kampanya=$hakedis=0;
        foreach ($this->kampanya as $i=>$k){


            if(in_array($k['kmp_tip'],$sepetKampanyalari)){

                if($sepetToplami > $k['kmp_minTutar'] && $k['kmp_minTutar'] > $tuttuKiyas){
                    $kampanya=$i;
                    $hakedis=$k['kmp_indirimMiktar'];
                    $tuttuKiyas=$k['kmp_minTutar'];
                }



            }

        }

        if($kampanya >0 && $hakedis >0){

            $this->kampanya[$kampanya]['kmp_hakedis']=$hakedis;
            foreach($this->basket['sepet']  as $s){
                $this->basket['sepet'][$s['urun']]['indirim']+= $s['adet']*((($s['sf']-$s['indirim']) * $hakedis)/($this->basket['toplam']-$this->basket['indirim']));
            }

        }
        $this->basket['indirim'] +=$hakedis;







    }
    public function kuponHesapla(){

// sepettutar= indirime esas alınacak tutar
        if($this->basket['kupon']['kmp_indirimTipi']==0){

            $sepetTutar=$this->kampanyaDisiTutar;

        }elseif($this->basket['kupon']['kmp_indirimTipi']==1){

            $sepetTutar=$this->basket['toplam']-$this->basket['indirim'];

        }elseif($this->basket['kupon']['kmp_indirimTipi']==2){
            $sepetTutar=$this->basket['toplam'];

        }





        if(!empty($this->basket['kupon']['kmp_aktif'])){

            if($this->basket['kupon']['kmp_indirimSekli']=='Y'){

                $indirimMiktar=round($sepetTutar*$this->basket['kupon']['kmp_indirimMiktar'] / 100,2);

            }elseif($this->basket['kupon']['kmp_indirimSekli']=='T'){

                $indirimMiktar=$this->basket['kupon']['kmp_indirimMiktar'];

            }

            if($indirimMiktar >0){
                $this->kampanya[$this->basket['kupon']['id']]['kmp_hakedis']=$indirimMiktar;
                $this->kampanya[$this->basket['kupon']['id']]['kmp_kisaAd']='Kupon ('.$this->basket['kupon']['kmp_kisaAd'].')';
                $this->basket['kuponIndirim']=$indirimMiktar;
                foreach($this->basket['sepet'] as $s){
                    $sf=floatval($this->basket['sepet'][$s['urun']]['sf']);
                    $indirim=floatval($this->basket['sepet'][$s['urun']]['indirim']);
                    $this->basket['sepet'][$s['urun']]['indirim']+=round((($sf-$indirim)*$indirimMiktar)/$sepetTutar,2);

                }

            }

        }



    }




    function Multisort_Array($arr,$v1,$v2,$v1Sort=SORT_ASC,$v2Sort=SORT_ASC){

        $tempArr = array();
        $keys = array_keys($arr);
        foreach($arr as $key=>$val) {
            $tempArr[$v1][$key] = $val[$v1];
            $tempArr[$v2][$key] = $val[$v2];
        }
        array_multisort($tempArr[$v1], $v1Sort, $tempArr[$v2], $v2Sort,$arr,$keys);
        $arr = array_combine($keys, $arr);
        return $arr;
    }
    function Multisort_ArrayNotKey($arr,$v1,$v2,$v1Sort=SORT_ASC,$v2Sort=SORT_ASC){

        $tempArr = array();

        foreach($arr as $key=>$val) {
            $tempArr[$v1][$key] = $val[$v1];
            $tempArr[$v2][$key] = $val[$v2];
        }
        array_multisort($tempArr[$v1], $v1Sort, $tempArr[$v2], $v2Sort,$arr);

        return $arr;
    }
    function valueSort($array,$key,$sortType=SORT_ASC){

        $keys = array_keys($array);
        $array_col = array_column($array, $key);
        array_multisort($array_col, $sortType, $array,$keys);
        $array = array_combine($keys, $array);
        return $array;
    }










}