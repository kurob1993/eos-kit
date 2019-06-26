<?php

    return [
        'absence' => [
            'url' => env('SI_ABSENCE','../public/wsdl/SI_ABSENCE.WSDL'),
            'api' => env('SI_ABSENCE_API', 'http://localhost/api/sap/absence')
        ],
        'absence_quota' => [
            'url' => env('SI_ABSENCE_QUOTA','../public/wsdl/SI_ABSENCE_QUOTA.WSDL'),
            'api' => env('SI_ABSENCE_QUOTA_API', 'http://localhost/api/absenceQuota')
        ],
        'attendance' => [
            'url' => env('SI_ATTENDANCE','../public/wsdl/SI_ATTENDANCE.WSDL'),
            'api' => env('SI_ATTENDANCE_API', 'http://localhost/api/attendance')
        ]
    ];