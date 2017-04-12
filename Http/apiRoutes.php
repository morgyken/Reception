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

$router->get('apointment/get/list', ['uses' => 'ApiController@get_schedule', 'as' => 'get_schedule']);
$router->delete('appointment/cancel/checkin', ['uses' => 'ApiController@cancel_checkin', 'as' => 'cancel_checkin']);
$router->get('appointment/change/destination', ['uses' => 'ApiController@change_destination', 'as' => 'change_destination']);
$router->delete('documents/delete', ['uses' => 'ApiController@delete_doc', 'as' => 'delete_doc']);
$router->get('appointment/appointments', ['uses' => 'ApiController@reschedule', 'as' => 'reschedule']);
$router->delete('appointment/cancel', ['uses' => 'ApiController@cancel_appointment', 'as' => 'cancel_appointment']);
$router->get('patients', ['uses' => 'ApiController@get_patients', 'as' => 'suggest_patients']);
$router->get('get_patients', ['uses' => 'ApiController@get_checkin_patients', 'as' => 'get_patients']);
