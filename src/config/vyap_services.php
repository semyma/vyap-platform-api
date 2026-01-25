<?php

return [
    'services' => [
        [
            'code' => 'billing',
            'name' => 'Billing',
            'description' => 'Billing, invoices, sales, reports.',
            'enabled' => true,

            'pricing' => [
                'currency' => 'INR',
                'plans' => [
                    [
                        'plan_code' => 'monthly',
                        'label' => 'Monthly',
                        'price' => 299,
                        'duration_days' => 30,
                        'recommended' => false,
                    ],
                    [
                        'plan_code' => 'yearly',
                        'label' => 'Yearly',
                        'price' => 1999,
                        'duration_days' => 365,
                        'recommended' => true,
                        'savings_note' => 'Save 2 months',
                    ],
                ],
            ],
        ],

        [
            'code' => 'rental',
            'name' => 'Rental',
            'description' => 'Tool rentals and due tracking.',
            'enabled' => true,

            'pricing' => [
                'currency' => 'INR',
                'plans' => [
                    [
                        'plan_code' => 'monthly',
                        'label' => 'Monthly',
                        'price' => 199,
                        'duration_days' => 30,
                    ],
                ],
            ],
        ],
        [
    'code' => 'delivery',
    'name' => 'Delivery',
    'description' => 'Delivery staff assignment and order tracking.',
    'enabled' => true,

    'pricing' => [
        'currency' => 'INR',
        'plans' => [
            [
                'plan_code' => 'monthly',
                'label' => 'Monthly',
                'price' => 249,
                'duration_days' => 30,
            ],
            [
                'plan_code' => 'yearly',
                'label' => 'Yearly',
                'price' => 1999,
                'duration_days' => 365,
                'recommended' => true,
            ],
        ],
    ],
],

    ],
];
