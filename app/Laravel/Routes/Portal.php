<?php

/*,'domain' => env("FRONTEND_URL", "wineapp.localhost.com")*/
Route::group(['as' => "portal.",
		 'namespace' => "Portal",
		 'middleware' => ["web"],
		 // 'domain' => env('SYSTEM_URL',''),
		],function() {

	Route::group(['middleware' => ["web","portal.auth"]], function(){
		Route::group(['prefix' => "application", 'as' => "application."], function () {
			Route::get('create',['as' => "create", 'uses' => "ApplicationController@create"]);
			Route::post('create',['uses' => "ApplicationController@store"]);
		});
	});
});