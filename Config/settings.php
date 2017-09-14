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
        'description' => 'Also check-in patients to nurse by default',
        'view' => 'checkbox',
        'hint' => 'check to allow'
    ],
    'purpose_of_visit' => [
        'description' => 'Display purpose of visit at check-in',
        'view' => 'checkbox',
        'hint' => 'check to allow'
    ],
];
