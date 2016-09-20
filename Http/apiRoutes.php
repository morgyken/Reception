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
    'namespace' => 'Ignite\Reception\Http\Controllers',
    'as' => 'api.reception.',
    'prefix' => 'api/reception',
    'middleware' => mconfig('core.core.middleware.api'),
];
//AJAX ONLY routes
Route::group($ajax, function() {
    Route::get('apointment/get/list', ['uses' => 'ApiController@get_schedule', 'as' => 'get_schedule']);
    Route::delete('appointment/cancel/checkin', ['uses' => 'ApiController@cancel_checkin', 'as' => 'cancel_checkin']);
    Route::get('appointment/change/destination', ['uses' => 'ApiController@change_destination', 'as' => 'change_destination']);
    Route::delete('documents/delete', ['uses' => 'ApiController@delete_doc', 'as' => 'delete_doc']);
    Route::get('appointment/appointments', ['uses' => 'ApiController@reschedule', 'as' => 'reschedule']);
    Route::delete('appointment/cancel', ['uses' => 'ApiController@cancel_appointment', 'as' => 'cancel_appointment']);
});
