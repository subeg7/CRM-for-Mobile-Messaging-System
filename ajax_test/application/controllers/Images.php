<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Images extends CI_Controller {
	function __construct()
	{
		parent::__construct();

	}
	public function load($image,$img1=NULL,$img2=NULL,$img3=NULL,$img4=NULL){
		$path_to = '';
		if($img4!=NULL){
			$img_comp = explode('.',trim($img4));
			$path_to = $image.'/'.$img1.'/'.$img2.'/'.$img3.'/'.$img4;
		}
		elseif($img3!=NULL){
			$img_comp = explode('.',trim($img3));
			$path_to = $image.'/'.$img1.'/'.$img2.'/'.$img3;
		}
		elseif($img2!=NULL){
			$img_comp = explode('.',trim($img2));
			$path_to = $image.'/'.$img1.'/'.$img2;
		}
		elseif($img1!=NULL){
			$img_comp = explode('.',trim($img1));
			$path_to = $image.'/'.$img1;
		}
		else{
			$img_comp = explode('.',trim($image));
			$path_to = $image;
		}

		$mime = array(
						'jpg'=>'image/jpg',
						'gif'=>'image/gif',
						'jpeg'=>'image/jpeg',
						'png'=>'image/png',
						'bmp'=>'image/bmp',
						'GIF'=>'image/gif',
						'JPEG'=>'image/jpeg',
						'PNG'=>'image/png',
						'BMP'=>'image/bmp',
						'JPG'=>'image/jpg',
					);

		$path ='assets/images/';

		if(file_exists($path.$path_to)) {

		 	header('content-type: '.$mime[$img_comp[(sizeof($img_comp)-1)]]);
		 	readfile($path.$path_to);
		 }
	}



	// end of  class
}
