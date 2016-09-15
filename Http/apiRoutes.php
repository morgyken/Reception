<?php

/*
 * =============================================================================
 *
 * Collabmed Solutions Ltd
 * Project: iClinic
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 *
 * =============================================================================
 */

$ajax = [
    'namespace' => 'Dervis\Modules\Reception\Http\Controllers',
    'as' => 'reception.ajax.',
    'prefix' => 'reception/ajax',
    'middleware' => ['ajax'],
];
//AJAX ONLY routes
Route::group($ajax, function() {

});
