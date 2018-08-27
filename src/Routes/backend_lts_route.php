<?php
Route::group(['prefix' => config('laravel_tagable.backend_lts_route_prefix'), 'namespace' => 'ArtinCMS\LTS\Controllers', 'middleware' => config('laravel_tagable.backend_lts_middlewares')], function () {
    Route::get('manageTag', ['as' => 'LTS.manageTag', 'uses' => 'TagController@manageTag']);
    Route::post('getTag', ['as' => 'LTS.getTag', 'uses' => 'TagController@getTag']);
    Route::post('saveTag', ['as' => 'LTS.saveTag', 'uses' => 'TagController@saveTag']);
    Route::post('getEditTagForm', ['as' => 'LTS.getEditTagForm', 'uses' => 'TagController@getEditTagForm']);
    Route::post('editTag', ['as' => 'LTS.editTag', 'uses' => 'TagController@editTag']);
    Route::post('trashTag', ['as' => 'LTS.trashTag', 'uses' => 'TagController@trashTag']);
    Route::post('setTagStatus', ['as' => 'LTS.setTagStatus', 'uses' => 'TagController@setTagStatus']);

    //-------------------------------------------autoComplete----------------------------------------------------------------------------------
    Route::post('autoCompleteTag', ['as' => 'LTS.autoCompleteTag', 'uses' => 'TagController@autoCompleteTag']);
});