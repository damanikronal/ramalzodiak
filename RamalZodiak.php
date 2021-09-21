<?php
/*******************************************************************************
 Deskripsi: 
 RamalZodiak adalah php class yang berfungsi mendapatkan (web scrapping)
 dan menampilkan konten ramalan (umum,percintaan dan keuangan) dari zodiak.

 Developer:
 Ronal Damanik
 lotbiner.my.id

 Notes:
 Selama web https://m.fimela.com/zodiak/ aktif maka script ini dipastikan bisa 
 tetap berjalan sebagaimana mestinya.
*******************************************************************************/
class RamalZodiak{
    //properti
    public $zodiak;
    private $url;
    private $konten;
    private $ramal_umum;
    private $ramal_cinta;
    private $ramal_keuangan;

    //Constructor untuk web scrapping konten ramalan zodiak dari https://m.fimela.com/zodiak/
    public function __construct($zodiak){
        $this->zodiak = strtolower($zodiak);
        $this->url= 'https://m.fimela.com/zodiak/'.$this->zodiak;
        //dapatkan konten
        $arrContextOptions=array(
			"ssl"=>array(
					"verify_peer"=>false,
					"verify_peer_name"=>false,
				),
			);
        $this->konten = file_get_contents($this->url, false, stream_context_create($arrContextOptions));
    }

    //Method untuk mendapatkan konten ramalan zodiak kondisi umum seseorang 
    public function GetRamalUmum(){
        $start = 'Umum';
        $end   = '<div class="zodiak--content__item zodiak--content__love"><h5 class="zodiak--content__item-title">Love</h5>';
        $startPosisition = strpos($this->konten, $start);
        $endPosisition   = strpos($this->konten, $end); 
        $longText = $endPosisition - $startPosisition;
        $this->RamalUmum = str_replace("Umum","",strip_tags(substr($this->konten, $startPosisition, $longText)));
        return $this->RamalUmum;
    }

    //Method untuk mendapatkan konten ramalan zodiak kondisi percintaan seseorang 
    public function GetRamalCinta(){
        $start = 'Love';
        $end   = '<div class="zodiak--content__item zodiak--content__keuangan"><h5 class="zodiak--content__item-title">Keuangan</h5>';
        $startPosisition = strpos($this->konten, $start);
        $endPosisition   = strpos($this->konten, $end); 
        $longText = $endPosisition - $startPosisition;
        $this->RamalCinta = str_replace("Love","",strip_tags(substr($this->konten, $startPosisition, $longText)));
        return $this->RamalCinta;
    }

    //Method untuk mendapatkan konten ramalan zodiak kondisi keuangan seseorang 
    public function GetRamalKeuangan(){
        $start = 'Keuangan';
        $end   = '<div class="zodiak--content__item zodiak--content__misc"><div class="zodiak--content__item-left"><h5 class="zodiak--content__item-title">Nomor</h5>';
        $startPosisition = strpos($this->konten, $start);
        $endPosisition   = strpos($this->konten, $end); 
        $longText = $endPosisition - $startPosisition;
        $this->RamalKeuangan = str_replace("Keuangan","",strip_tags(substr($this->konten, $startPosisition, $longText)));;
        return $this->RamalKeuangan;
    }

}

//Contoh implementasi
$zodiak = "leo";
echo "<h3>Ramalan ".ucfirst(strtolower($zodiak))." hari ini.</h3>";
$rz = new RamalZodiak($zodiak);

echo "<b>Umum :</b><br/>";
echo $rz->GetRamalUmum()."<br/>";

echo "<b>Percintaan :</b><br/>";
echo $rz->GetRamalCinta()."<br/>";

echo "<b>Keuangan :</b><br/>";
echo $rz->GetRamalKeuangan()."<br/>";