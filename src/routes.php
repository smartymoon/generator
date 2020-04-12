<?php

Route::group(['namespace' => 'Smartymoon\Generator\Controllers'], function() {
    Route::get('lee', 'HomeController@index');

    Route::post('lee', 'HomeController@store');

    Route::post('drop-table', 'HomeController@drop');
});
