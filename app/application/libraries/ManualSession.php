<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  class ManualSession{
    public $manualSessionStore;
    function __construct(){
      echo"session store initialzed";
      $this->manualSessionStore="not set yet";
      echo"manualSessionStore: ".$this->manualSessionStore;
    }

    public function setStore($string){
      $this->manualSessionStore=$string;
    }

  }
