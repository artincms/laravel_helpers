<?php
Route::group(['prefix' => config('laravel_tagable.frontend_lts_route_prefix'), 'namespace' => 'ArtinCMS\LTS\Controllers', 'middleware' => config('laravel_tagable.frontend_lts_middlewares')], function () {
    Route::post('chnageLike', ['as' => 'LTS.chnageLike', 'uses' => 'TagController@chnageLike']);
});