<?php

Route::group(['namespace' => 'Smartymoon\Generator\Controllers'], function() {
    Route::get('lee', 'HomeController@index');

    Route::get('lee/modules', 'HomeController@modules');

    Route::post('lee', 'HomeController@store');

    Route::post('drop-table', 'HomeController@drop');
});
