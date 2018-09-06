<?php
namespace Artincms\LHS\Helpers\Classes;

class Fdata {
 

    private $notifi;
    private $is_background;
    private $jdata;

 
    function __construct() {
         
    }
 
    public function setNotifi($notifi) {
        $this->notifi = $notifi;
    }
 
    public function setBackground($background) {
        $this->is_background = $background;
    }
 
    public function setJdata($jdata) {
        $this->jdata = $jdata;
    }
 

 
    public function getPush() {
        $res = array();
        $res['data']['notifi'] = $this->notifi;
        $res['data']['is_background'] = $this->is_background;
        $res['data']['jdata'] = $this->jdata;
        return $res;
    }
 
}