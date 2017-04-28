<?php

use Illuminate\Routing\Router;

$router->get('patients/add/{id?}', ['uses' => 'ReceptionController@add_patient', 'as' => 'add_patient']);
$router->get('patients/purge/{id?}', ['uses' => 'ReceptionController@purge_patient', 'as' => 'purge_patient']);
$router->post('patients/save/{id?}', ['uses' => 'ReceptionController@save_patient', 'as' => 'save_patient']);
$router->post('patients/update/', ['uses' => 'ReceptionController@save_patient', 'as' => 'update_patient']);
$router->get('patients/show', ['as' => 'show_patients', 'uses' => 'ReceptionController@show_patients']);
// $router->match(['post', 'get'], 'patients/edit/{id?}', ['uses' => 'ReceptionController@edit_patient', 'as' => 'edit_patient']);
$router->get('patients/view/{id}', ['uses' => 'ReceptionController@view_patient', 'as' => 'view_patient']);
$router->get('patient/schedule/show/{id?}', ['uses' => 'ReceptionController@appointments', 'as' => 'appointments']);
$router->post('patient/schedule/save/{id?}', ['uses' => 'ReceptionController@appointments_save', 'as' => 'appointments.save']);
$router->post('patient/reschedule/', ['uses' => 'ReceptionController@appointments_res', 'as' => 'appointments.res']);
$router->get('patients/calendar', ['uses' => 'ReceptionController@calendar', 'as' => 'calendar']);
$router->get('patients/google/calendar', ['uses' => 'ReceptionController@google_calendar', 'as' => 'google_calendar']);
$router->get('patients/documents', ['uses' => 'ReceptionController@documents', 'as' => 'patient_documents']);
$router->match(['post', 'get'], 'patients/upload/docs/{id}', ['uses' => 'ReceptionController@upload_doc', 'as' => 'upload_doc']);
$router->get('checkin/{id?}/{visit?}', ['uses' => 'ReceptionController@checkin', 'as' => 'checkin']);
$router->post('do_check/{id?}/{visit?}', ['uses' => 'ReceptionController@do_check', 'as' => 'do_check']);
$router->get('patients/queue', ['as' => 'patients_queue', 'uses' => 'ReceptionController@patients_queue']);
$router->get('patients/manage/queued/{visit_id}', ['as' => 'manage_checkin', 'uses' => 'ReceptionController@manage_checkin']);
$router->match(['get', 'post'], 'patients/visit/new/{visit_id}', ['as' => 'new_visit', 'uses' => 'ReceptionController@new_visit']);
$router->get('patients/view/document/{document}', ['as' => 'view_document', 'uses' => 'ReceptionController@document_viewer']);
$router->get('patients/skipper', ['as' => 'skipper', 'uses' => 'ReceptionController@Skipper']);
//settings
$router->group(['prefix' => 'setup', 'as' => 'setup.'], function (Router $router) {
    $router->get('appointment/cat/show/{category?}', ['as' => 'app_category', 'uses' => 'SetupController@app_category']);
    $router->get('consultation/', ['as' => 'consultation', 'uses' => 'SetupController@consultation']);
    $router->post('appointment/cat/save', ['as' => 'app_category.save', 'uses' => 'SetupController@save_app_category']);
});
