<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Attribute;

class AttributeController extends Controller
{

	public function insertOrUpdate($prodId, $attribs){
//dd($attribs);
		$response = false;
		foreach ($attribs as $attrib) {
			$search =		
				Attribute::select()
		          ->where('product_id', $prodId)
		          ->where('name', $attrib[0])
		          ->first();

			if (isset($search) &&
				$search !== '' &&
				$search->count() > 0) {
				$myAttrib = $search;
				if (!($myAttrib->value === $attrib[1] &&
					$myAttrib->complement === $attrib[2])
					) {
					$response = true;
					$myAttrib->value = $attrib[1];
					$myAttrib->complement = $attrib[2];
					$myAttrib->save();
				}
			} else {
				$myAttrib = new Attribute();
				$response = true;
				$myAttrib->product_id = $prodId;
				$myAttrib->name = $attrib[0];
				$myAttrib->value = $attrib[1];
				$myAttrib->complement = $attrib[2];
				$myAttrib->save();
			}
		}
		return $response;
	}

}
