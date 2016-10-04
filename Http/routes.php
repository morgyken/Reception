<?php

$group = [
    'middleware' => mconfig('core.core.middleware.backend'),
    'prefix' => 'reception',
    'as' => 'reception.',
    'namespace' => 'Ignite\Reception\Http\Controllers'];
Route::group($group, function() {
    Route::get('patients/add/{id?}', ['uses' => 'ReceptionController@add_patient', 'as' => 'add_patient']);
    Route::post('patients/save/{id?}', ['uses' => 'ReceptionController@save_patient', 'as' => 'save_patient']);
    Route::get('patients/show', ['as' => 'show_patients', 'uses' => 'ReceptionController@show_patients']);
    // Route::match(['post', 'get'], 'patients/edit/{id?}', ['uses' => 'ReceptionController@edit_patient', 'as' => 'edit_patient']);
    Route::get('patients/view/{id}', ['uses' => 'ReceptionController@view_patient', 'as' => 'view_patient']);
    Route::get('patient/schedule/show/{id?}', ['uses' => 'ReceptionController@appointments', 'as' => 'appointments']);
    Route::post('patient/schedule/save/{id?}', ['uses' => 'ReceptionController@appointments_save', 'as' => 'appointments.save']);
    Route::get('patients/appointments/new', ['uses' => 'ReceptionController@new_appointment', 'as' => 'appointments.new']);
    Route::get('patients/calendar', ['uses' => 'ReceptionController@calendar', 'as' => 'calendar']);
    Route::get('patients/google/calendar', ['uses' => 'ReceptionController@google_calendar', 'as' => 'google_calendar']);
    Route::get('patients/documents', ['uses' => 'ReceptionController@documents', 'as' => 'patient_documents']);
    Route::match(['post', 'get'], 'patients/upload/docs/{id}', ['uses' => 'ReceptionController@upload_doc', 'as' => 'upload_doc']);
    Route::get( 'checkin/{id?}/{visit?}', ['uses' => 'ReceptionController@checkin', 'as' => 'checkin']);
    Route::post( 'do_check/{id?}/{visit?}', ['uses' => 'ReceptionController@do_check', 'as' => 'do_check']);
    Route::get('patients/queue', ['as' => 'patients_queue', 'uses' => 'ReceptionController@patients_queue']);
    Route::get('patients/manage/queued/{visit_id}', ['as' => 'manage_checkin', 'uses' => 'ReceptionController@manage_checkin']);
    Route::match(['get', 'post'], 'patients/visit/new/{visit_id}', ['as' => 'new_visit', 'uses' => 'ReceptionController@new_visit']);
    Route::get('patients/view/document/{document}', ['as' => 'view_document', 'uses' => 'ReceptionController@document_viewer']);
});
