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
    'relationship' => [
        1 => 'Father',
        2 => 'Mother',
        3 => 'Guardian',
        4 => 'Brother',
        5 => 'Sister',
        6 => 'Cousin',
        7 => 'Grandmother',
        8 => 'Grandfather',
        9 => 'Friend',
        10 => 'Husband',
        11 => 'Wife',
        12 => 'Other',
        13 => 'Self',
        14 => 'Uncle',
        15 => 'Aunt',
        16 => 'Son',
        17 => 'Daughter',
    ],
    'document_types' => [
        1 => 'Job',
        2 => 'Lab Report',
        3 => 'Medical Report',
        3 => 'Lab Tests',
        4 => 'Patient History',
        5 => 'Radiology Tests',
        6 => 'X-RAY Records',
        7 => 'Other Reports',
    ],
    'schedule_categories' => [
        1 => 'Consultation',
        2 => 'Lab',
        3 => 'Surgery',
        4 => 'Theatre',
        5 => 'Diagnostics',
    ],
    'destinations' => [
        'laboratory' => 'Laboratory',
        'theatre' => 'Theatre',
        'diagnostics' => 'Diagnostics',
        'radiology' => 'Radiology',
        'pharmacy' => 'Pharmacy',
        'optical' => 'Optical',
    ],
    'checkin_purposes' => [
        1 => 'First time consultation',
        2 => 'Review after surgery',
        3 => 'Follow up review',
        4 => 'General Consultation',
    ],
    'visit_status' => [
        1 => 'Scheduled',
        2 => 'Checked In',
        3 => 'Checked Out',
        3 => 'Cancelled',
        4 => 'Rescheduled',
        5 => 'Proposed',
        6 => 'No show',
    ],
];
