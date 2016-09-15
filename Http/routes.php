<?php

$group = [
    'middleware' => ['auth.admin', 'setup'],
    'prefix' => 'reception',
    'as' => 'reception.',
    'namespace' => 'Ignite\Reception\Http\Controllers'];
Route::group($group, function() {
    Route::get('/', ['uses' => 'ReceptionController@index', 'as' => 'index']);
    Route::match(['get', 'post'], 'patients/add/{id?}', ['uses' => 'ReceptionController@add_patient', 'as' => 'add_patient']);
    Route::get('patients/list/', ['as' => 'manage_patients', 'uses' => 'ReceptionController@manage_patients']);
    // Route::match(['post', 'get'], 'patients/edit/{id?}', ['uses' => 'ReceptionController@edit_patient', 'as' => 'edit_patient']);
    Route::get('patients/view/{id}', ['uses' => 'ReceptionController@view_patient', 'as' => 'view_patient']);
    Route::match(['post', 'get'], 'patient/schedule/{id?}', ['uses' => 'ReceptionController@patient_schedule', 'as' => 'patient_schedule']);
    Route::get('patients/appointments', ['uses' => 'ReceptionController@appointments', 'as' => 'appointments']);
    Route::get('patients/calendar', ['uses' => 'ReceptionController@calendar', 'as' => 'calendar']);
    Route::get('patients/google/calendar', ['uses' => 'ReceptionController@google_calendar', 'as' => 'google_calendar']);
    Route::get('patients/documents', ['uses' => 'ReceptionController@documents', 'as' => 'patient_documents']);
    Route::match(['post', 'get'], 'patients/upload/docs/{id}', ['uses' => 'ReceptionController@upload_doc', 'as' => 'upload_doc']);
    Route::match(['get', 'post'], 'checkin/{id?}/{visit?}', ['uses' => 'ReceptionController@checkin', 'as' => 'checkin']);
    Route::get('patients/queue', ['as' => 'patients_queue', 'uses' => 'ReceptionController@patients_queue']);
    Route::get('patients/manage/queued/{visit_id}', ['as' => 'manage_checkin', 'uses' => 'ReceptionController@manage_checkin']);
    Route::match(['get', 'post'], 'patients/visit/new/{visit_id}', ['as' => 'new_visit', 'uses' => 'ReceptionController@new_visit']);
    Route::get('patients/view/document/{document}', ['as' => 'view_document', 'uses' => 'ReceptionController@document_viewer']);
});
