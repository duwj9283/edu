<?php
class QRC{
    private $errorCorrectionLevel = 'H';		// L M Q H

    private $matrixPointSize = 3;				// 1 2 3 4 5 6 7 8 9 10

    private $url = '';				// 1 2 3 4 5 6 7 8 9 10

    public function set($key,$value){
        $this->$key = $value;
    }
    public function init(){
        include APP_PATH."/lib/phpqrcode/qrlib.php";
        $url = $this->url;
        QRcode::png($url, false, $this->errorCorrectionLevel, $this->matrixPointSize);
    }
}