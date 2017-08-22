<?php

namespace App\Http\Controllers\Suppliers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\CrawlerController;

class SupplierController extends Controller {
	private $prodCtrl;
	private $attribCtrl;
	private $crawlerCtrl;

	public function __construct(){
		$this->crawlerCtrl = new CrawlerController();
	}


	public function getData($url, $reqElements){
		return $this->crawlerCtrl->getData($url, $reqElements);
	}
	public function getLinks($url){
		return $this->crawlerCtrl->getLinks($url);
	}

	public function loginViaPost(
		$url, 
		$username, 
		$fieldUserName, 
		$passwd, 
		$fieldpasswrd) {
		$this->crawlerCtrl
			->loginViaPost(
				$url, 
				$username, 
				$fieldUserName, 
				$passwd, 
				$fieldpasswrd);
	}
}
