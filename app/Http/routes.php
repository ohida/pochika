<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// index
Route::get('/', ['as' => 'root', 'uses' => 'SiteController@index']);

// index with page
Route::get('/page/{page}', ['as' => 'index_paged', 'uses' => 'SiteController@index'])
	->where('page', '[\d]+');

// index with tag
Route::get('/tag/{tag}/{page?}', ['as' => 'index_tagged', 'uses' => 'SiteController@tag'])
	->where('page', '[\d]+');

// post
Route::get('/{year}/{month}/{day}/{slug}', ['as' => 'post', 'uses' => 'SiteController@post'])
	->where([
		'year' => '[\d]{4}',
		'month' => '[\d]{2}',
		'day' => '[\d]{2}',
		'slug' => '[\w\-]+',
	]);

// archives
Route::get('/archives', ['as' => 'archives', 'uses' => 'SiteController@archives']);

// archives pagination
Route::get('/archives/page/{page}', ['as' => 'archives_paged', 'uses' => 'SiteController@archives'])
	->where('page', '[\d]+');

// search
Route::get('/search', ['as' => 'search', 'uses' => 'SiteController@search']);

// feed
Route::get('/feed', ['as' => 'feed', 'uses' => 'SiteController@feed']);

// sitemap
Route::get('/sitemap{extention?}', ['as' => 'sitemap', 'uses' => 'SiteController@sitemap'])
	->where('extention', '\.xml');

//// clear cache (debug)
//Route::get('/_clear', ['uses' => 'SiteController@_clear']);

// page
Route::get('/{a0}/{a1?}/{a2?}/{a3?}/{a4?}', ['as' => 'page', 'uses' => 'SiteController@page'])
	->where([
		'a0' => '^[^_].*', // for _debugbar ...
	]);

// page
//Route::get('/{name}', ['as' => 'page', 'uses' => 'SiteController@page']);
// page (dir)
//Route::get('/{dir}/{name}', ['as' => 'page_dir', function($dir, $name) {
//	return App::make('App\Http\Controllers\SiteController')->page([$dir, $name]);
//}]);

/*
|--------------------------------------------------------------------------
| Authentication & Password Reset Controllers
|--------------------------------------------------------------------------
|
| These two controllers handle the authentication of the users of your
| application, as well as the functions necessary for resetting the
| passwords for your users. You may modify or remove these files.
|
*/

//Route::controllers([
//	'auth' => 'Auth\AuthController',
//	'password' => 'Auth\PasswordController',
//]);

