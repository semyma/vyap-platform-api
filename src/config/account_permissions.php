<?php

declare(strict_types=1);

return [
    'roles' => [
        'owner' => ['*'],

        'admin' => [
            'team.view',
            'team.manage',

            'billing.sales.create',
            'billing.sales.view',
            'billing.sales.refund',
            'billing.reports.view',

            'store.view',
            'store.subscribe',

            // Delivery
            'delivery.manage',
            'delivery.view',
        ],

        'staff' => [
            'team.view',

            'billing.sales.view',
            'billing.reports.view',

            'store.view',

            // Delivery
            'delivery.view',
        ],

        'cashier' => [
            'billing.sales.create',
            'billing.sales.view',

            'store.view',
        ],

        'delivery' => [
            // Delivery staff permissions
            'delivery.self',
        ],
    ],

    'permissions' => [
        'delivery.manage' => [
            'label' => 'Manage deliveries',
            'description' => 'Create, assign/reassign deliveries and record collection.',
        ],

        'delivery.self' => [
            'label' => 'Delivery staff access',
            'description' => 'View assigned deliveries and update status.',
        ],

        'delivery.view' => [
            'label' => 'View deliveries',
            'description' => 'View delivery list and details.',
        ],
    ],
];
