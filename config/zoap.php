<?php

return [
    
    // Service configurations.

    'services'          => [       
        'demo'              => [
            'name'              => 'Demo',
            'class'             => 'Viewflex\Zoap\Demo\DemoService',
            'exceptions'        => [
                'Exception'
            ],
            'types'             => [
                'keyValue'          => 'Viewflex\Zoap\Demo\Types\KeyValue',
                'product'           => 'Viewflex\Zoap\Demo\Types\Product'
            ],
            'strategy'          => 'ArrayOfTypeComplex',
            'headers'           => [
                'Cache-Control'     => 'no-cache, no-store'
            ]
        ],
        'absencequota' => [
            'name' => 'SI_ABSENCE_QUOTA',
            'class' => 'App\Zoap\AbsenceQuota\AbsenceQuotaService',
            'exceptions' => [ 
                'Exception' 
            ],
            'types' => [
                'keyValue' => 'App\Zoap\AbsenceQuota\Types\KeyValue',
                'product' => 'App\Zoap\AbsenceQuota\Types\Product'
            ],
            'strategy' => 'ArrayOfTypeComplex',
            'headers' => [
                'Cache-Control' => 'no-cache, no-store'
            ]
        ]
        
    ],

    
    // Log exception trace stack?

    'logging'       => true,

    
    // Mock credentials for demo.

    'mock'          => [
        'user'              => 'test@test.com',
        'password'          => 'tester',
        'token'             => 'tGSGYv8al1Ce6Rui8oa4Kjo8ADhYvR9x8KFZOeEGWgU1iscF7N2tUnI3t9bX'
    ],

    
];
