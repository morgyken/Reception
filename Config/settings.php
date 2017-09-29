<?php

/*
 * =============================================================================
 *
 * Collabmed Solutions Ltd
 * Project: Collabmed Health Platform
 * Author: Samuel Okoth <sodhiambo@collabmed.com>
 *
 * =============================================================================
 */
return [
    'patient_id_abr' => [
        'description' => 'Patient ID Abbreviation',
        'view' => 'text'
    ],
    'checkin_destinations' => [
        'description' => 'Checkin destination',
        'view' => 'reception::fields.checkin',
        'hint' => 'Select users who patients can be sent to'
    ],
    'checkin_places' => [
        'description' => 'Checkin places',
        'view' => 'reception::fields.places',
        'hint' => 'Select destinations for patients'
    ],
    'pre_charged_compulsory' => [
        'description' => 'Make charging of consultation fee or any other fee compulsory',
        'view' => 'reception::fields.pre_charged',
        'hint' => 'Select fees to be made compulsory'
    ],
    'checkin_to_nurse' => [
        'description' => 'Allow checkin to nurse by default',
        'view' => 'checkbox'
    ],
    'purpose_of_visit' => [
        'description' => 'Enable selection of purpose of visit when checking in patients',
        'view' => 'checkbox'
    ],
    'external_doctor' => [
        'description' => 'Enable external doctor',
        'view' => 'checkbox'
    ],
    'checkin_to_rooms' => [
        'description' => 'Enable checkin to rooms',
        'view' => 'checkbox'
    ]
];
