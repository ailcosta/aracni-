<?php

namespace App\Http\Controllers\Suppliers;
      
use App\Http\Controllers\Suppliers\SupplierController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductController;
use App\Supplier;

class Cer2aController extends Controller {

    private $supplier;
	private $supplierCtrl;
	private $productCtrl;
	private $dimensionNames=array('height', 'width', 'length');

	public function __construct(){
		$this->supplierCtrl = new SupplierController();
		$this->productCtrl = new ProductController();

		$this->supplier 	= 
			Supplier::select()
	          ->where('name', 'like','2A Cer%')
	          ->first()
	          ->get()[0];
        //$this->supplierCtrl->loginMethod01(
        $this->supplierCtrl->loginViaPost(
        		$this->supplier->login_url, 
        		$this->supplier->user, 
        		'email', 
        		$this->supplier->password, 
        		'senha');
      	//dd($this->supplier);
	}

	public function getData($url){
		$reqElements = array();
		$newElement = array(
						'name' => 'description',
						'type' => 'text',
						'xPath' => "/html/body/div[1]/div[3]/div/div[3]/div[3]/div[3]/p[1]"
						);
		array_push($reqElements, $newElement);
		$newElement = array(
						'name' => 'price',
						'type' => 'text',
						'xPath' => "/html/body/div[1]/div[3]/div/div[3]/div[3]/div[3]/p[3]"
						);
		array_push($reqElements, $newElement);
		$newElement = array(
						'name' => 'dimensions',
						'type' => 'text',
						'xPath' => "/html/body/div[1]/div[3]/div/div[3]/div[3]/div[3]/p[2]"
						);
		array_push($reqElements, $newElement);
		$newElement = array(
						'name' => 'sku',
						'type' => 'text',
						'xPath' => "/html/body/div[1]/div[3]/div/div[3]/div[3]/div[3]/p[1]/span"
						);
		array_push($reqElements, $newElement);
		$newElement = array(
						'name' => 'image',
						'type' => 'img',
						'xPath' => "/html/body/div[1]/div[3]/div/div[3]/div[3]/div[2]/div/img"
						);
		array_push($reqElements, $newElement);
		$newElement = array(
						'name' => 'line',
						'type' => 'text',
						'xPath' => "/html/body/div[1]/div[3]/div/div[3]/div[3]/div[1]"
						);
		array_push($reqElements, $newElement);
		$resElements = $this->supplierCtrl->getData($url, $reqElements);
		$price = str_replace(',','.',str_replace(' ','',str_replace('R$','',$resElements['price']))) + 0;
		$prod=array();
		$prod['supplier_id'    ] = $this->supplier->id; //  int(10) UN 
		$prod['url'            ] = $url; //  varchar(255) 

		$sku = str_replace('Código: ','',$resElements['sku']);

		$prod['sku'            ] = str_replace(' ','_',$this->supplier->alias.$sku); //  varchar(25) 
		$description  = $resElements['description'];
		$description  = substr($description,0,stripos($description, 'digo:')-3);
		$prod['name'           ] = $description; //  varchar(200) 
		$prod['description'    ] = $description.'Linha'.$resElements['line']; //  text 
		$prod['qty'            ] = 1; //  int(11) 
		$prod['multiplicity'   ] = 1; //  int(11) 
		$prod['unit_price'     ] = $price; //  double 
//dd($prod);

		//Load attributes
		$attribs=array();
		$attribs[] = array("image", $this->supplier->base_url.$resElements['image'], "img");
//		$this->productCtrl->insertOrUpdate(
//			);
		$dimensions = str_replace(' ','',
						str_replace('cm','',
						str_replace('Dimensões:','',$resElements['dimensions'])
						));
		$position = stripos($dimensions, 'Ø');
		if ($position !== false) {
			$value = substr($dimensions,$position+2);
			$dimensions = substr($dimensions,0,$position);
//var_dump($dimensions);

			$attribs[] = array("diameter", $value, 'cm');
		}
		$dims = explode('x',strtolower($dimensions));
//dd($dims);
		$i=0;
		foreach ($dims as $dim) {
			if ($dim !== '') {
				$attribs[] = array($this->dimensionNames[$i],$dim, 'cm');
			}
			++$i;
		}
		$this->productCtrl->insertOrUpdate($prod, $attribs);

	}
	public function getLinks(){
		var_dump($this->supplier->main_url);		
		$linksLevel0 = $this->supplierCtrl->getLinks($this->supplier->main_url);
		foreach ($linksLevel0 as $link) {
			if (substr($link->getAttribute('href'),0,6) == 'linha/') {

				$linksLevel1 = $this->supplierCtrl->getLinks($this->supplier->base_url.$link->getAttribute('href'));
				foreach ($linksLevel1 as $link1) {
					if (substr($link1->getAttribute('href'),0,11) == 'produto-2a/') {;
				   		var_dump($link1->getAttribute('href'));
				   		$this->getData($this->supplier->base_url.$link1->getAttribute('href'));
			   		}
				}
//dd($linksLevel1);
			}
		}
	}


}