<?php

	Route::get('/', function () {
	    return view('welcome');
	});


	Route::get('/getLinks', 'Suppliers\Cer2aController@getLinks');
