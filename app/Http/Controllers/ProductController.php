<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;
use App\Http\Controllers\AttributeController;

class ProductController extends Controller
{
	private $attributeCtrl;
	public function __construct(){
		$this->attributeCtrl = new AttributeController();
	}
    
	public function insertOrUpdate($prod, $attribs){
//dd($attribs);
		$search =		
			Product::select()
	          ->where('sku', $prod['sku'])
	          ->first();
		if (isset($search) &&
			$search !== '' &&
			$search->count() > 0) {
			$myProd = $search;
			//$myProd->update_required = false;
		} else {
			$myProd = new Product();
			$myProd->update_required = true;
		}
		if (($myProd->update_required) ||
			(
				$myProd->name <> $prod['name'] ||
				$myProd->description <> $prod['description'] ||
				$myProd->qty <> $prod['qty'] ||
				$myProd->multiplicity <> $prod['multiplicity'] ||
				$myProd->unit_price <> $prod['unit_price']
				)

			) {
			$myProd->sku              = $prod['sku'];
            $myProd->url              = $prod['url'];
            $myProd->supplier_id      = $prod['supplier_id'];
            $myProd->update_required  = true;
            $myProd->name             = $prod['name'];
            $myProd->description      = $prod['description'];
            $myProd->qty              = $prod['qty'];
            $myProd->multiplicity     = $prod['multiplicity'];
            $myProd->unit_price       = $prod['unit_price'];
		}
		$myProd->verified_at = date('Y-m-d H:i:s');
		$myProd->save();
		$hadChange = $this->attributeCtrl->insertOrUpdate($myProd->id, $attribs);
		if ($hadChange &&
			(! $myProd->update_required)
			) {
			$myProd->update_required  = true;
			$myProd->save();
		}
	}

}
