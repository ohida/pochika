<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// index
Route::get('/', ['as' => 'root', 'uses' => 'SiteController@index']);

// index pagination
Route::get('/page/{page}', ['as' => 'index_paged', 'uses' => 'SiteController@index'])
->where('page', '[\d]+');

// index tag
Route::get('/tag/{tag}/{page?}', ['as' => 'index_tagged', 'uses' => 'SiteController@tag'])
->where('page', '[\d]+');

// post
Route::get('/{year}/{month}/{day}/{slug}', ['as' => 'post', function() {
    $name = implode('-', func_get_args());
    return App::make('SiteController')->post($name);
}])
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

// feed
Route::get('/feed', ['as' => 'feed', 'uses' => 'SiteController@feed']);

// search
Route::get('/search', ['as' => 'search', 'uses' => 'SiteController@search']);

// page
Route::get('/{name}', ['as' => 'page', 'uses' => 'SiteController@page']);

// page (dir/name)
#todo fix route name
Route::get('/{dir}/{name}', ['as' => 'page_dir', function($dir, $name) {
    return App::make('SiteController')->page([$dir, $name]);
}]);
