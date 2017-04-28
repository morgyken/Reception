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
];
