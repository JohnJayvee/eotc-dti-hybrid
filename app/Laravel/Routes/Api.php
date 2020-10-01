<?php

/*,'domain' => env("FRONTEND_URL", "api.highlysucceed.com")*/
Route::group(['as' => "api.",
		 'namespace' => "Api",
		 'middleware' => ["api.valid_format"],
		 'domain' => env('API_URL',''),
		 'prefix' => "api"
		],function() {


	Route::group(['as' => "auth."],function(){

		Route::group(['prefix' => "citizen"],function(){
			Route::post('login.{format}',['as' => "login",'uses' => "AuthController@authenticate"]);

			Route::post('logout.{format}',['as' => "logout",'uses' => "AuthController@logout",'middleware' => "api.jwt.auth:citizen"]);

			Route::post('check-login.{format}',['as' => "check_login",'uses' => "AuthController@check_login",'middleware' => "api.jwt.auth:citizen"]);
			Route::post('refresh-token.{format}',['as' => "refresh_token",'uses' => "AuthController@refresh_token",'middleware' => "api.jwt.auth:citizen"]);

			Route::post('pre-register.{format}',['as' => "pre_register",'uses' => "AuthController@pre_register"]);
			Route::post('register.{format}',['as' => "store",'uses' => "AuthController@store"]);

		});

		Route::group(['prefix' => "officer"],function(){
			Route::post('login.{format}',['as' => "login",'uses' => "AuthController@authenticate"]);

			Route::post('logout.{format}',['as' => "logout",'uses' => "AuthController@logout",'middleware' => "api.jwt.auth:officer"]);

			Route::post('check-login.{format}',['as' => "check_login",'uses' => "AuthController@check_login",'middleware' => "api.jwt.auth:officer"]);
			Route::post('refresh-token.{format}',['as' => "refresh_token",'uses' => "AuthController@refresh_token",'middleware' => "api.jwt.auth:officer"]);

			Route::post('pre-register.{format}',['as' => "pre_register",'uses' => "AuthController@pre_register"]);

			Route::post('register.{format}',['as' => "store",'uses' => "AuthController@store_officer",'middleware' => ["api.exist:reference_code","api.exist:available_reference_code"]]);

			Route::post('validate.{format}',['as' => "validate_code",'uses' => "AuthController@validate_code",'middleware' => "api.exist:reference_code"]);


		});

		
	});


	Route::group(['prefix' => "setting",'as' => 'setting.'],function(){
		Route::post('barangay.{format}',['as' => 'get_barangay', 'uses' => "SettingController@get_barangay"]);
	});

	Route::group(['prefix' => "article",'as' => 'article.','middleware' => "api.jwt.auth:citizen"],function(){
		Route::post('all.{format}',['as' => 'index', 'uses' => "ArticleController@index"]);
		Route::post('show.{format}',['as' => 'show', 'uses' => "ArticleController@show",'middleware' => "api.exist:article"]);
	});

	Route::group(['prefix' => "scanner",'as' => 'scanner.','middleware' => "api.jwt.auth:officer"],function(){
		Route::post('qr.{format}',['as' => 'scan_qr', 'uses' => "ScannerController@scan_qr"]);
		Route::post('report.{format}',['as' => 'submit_report', 'uses' => "ScannerController@submit_report"]);

	});


	Route::group(['prefix' => "citizen-identification",'as' => 'citizen_identification.'],function(){
		Route::group(['middleware' => "api.jwt.auth:citizen"],function(){
			Route::post('apply.{format}',['as' => 'apply', 'uses' => "CitizenIdentificationController@apply",'middleware' => "api.not_resident"]);
		});
	});


	//citizen  route
	Route::group(['prefix' => "citizen",'as' => "citizen.",'middleware' => "api.jwt.auth:citizen"],function(){
		
		Route::group(['prefix' => "profile",'as' => 'profile.'],function(){
			Route::post('show.{format}',['as' => 'show', 'uses' => "ProfileController@show"]);

			Route::post('id-card.{format}',['as' => 'identification_card', 'uses' => "ProfileController@identification_card"]);
			Route::post('travel-pass.{format}',['as' => 'travel_pass', 'uses' => "ProfileController@travel_pass"]);

			Route::post('edit-profile.{format}',['as' => 'update_profile', 'uses' => "ProfileController@update_profile"]);

			Route::post('edit-password.{format}',['as' => 'update_password', 'uses' => "ProfileController@update_password"]);
			Route::post('edit-avatar.{format}',['as' => 'update_avatar', 'uses' => "ProfileController@update_avatar"]);
		});

		Route::group(['prefix' => "travel-history",'as' => 'travel_history.'],function(){
			Route::post('detail.{format}',['as' => 'show', 'uses' => "TravelHistoryController@show",'middleware' => "api.exist:travel"]);
			Route::post('all.{format}',['as' => 'index', 'uses' => "TravelHistoryController@index"]);
		});

	});

	//officer  route
	Route::group(['prefix' => "officer",'as' => "officer.",'middleware' => "api.jwt.auth:officer"],function(){
		
		Route::group(['prefix' => "profile",'as' => 'profile.'],function(){
			Route::post('show.{format}',['as' => 'show', 'uses' => "ProfileController@show"]);
			
			Route::post('edit-profile.{format}',['as' => 'update_profile', 'uses' => "ProfileController@update_profile"]);

			Route::post('edit-password.{format}',['as' => 'update_password', 'uses' => "ProfileController@update_password"]);
			Route::post('edit-avatar.{format}',['as' => 'update_avatar', 'uses' => "ProfileController@update_avatar"]);
		});

		Route::group(['prefix' => "travel-history",'as' => 'travel_history.'],function(){
			Route::post('create.{format}',['as' => 'store', 'uses' => "TravelHistoryController@store"]);
			Route::post('show.{format}',['as' => 'show', 'uses' => "TravelHistoryController@show",'middleware' => "api.exist:travel"]);
			Route::post('daily.{format}',['as' => 'daily', 'uses' => "TravelHistoryController@daily"]);


			
		});
	});
	//Route for Api transaction
	Route::group(['prefix' => "transaction",'as' => 'transaction.'],function(){
		Route::post('store.{format}',['as' => 'store', 'uses' => "TransactionController@store"]);
		Route::post('inquire.{format}',['as' => 'show', 'uses' => "TransactionController@show"]);
	});

});