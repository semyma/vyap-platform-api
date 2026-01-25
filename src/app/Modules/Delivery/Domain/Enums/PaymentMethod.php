<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Domain\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case Upi = 'upi';
    case Bank = 'bank';
    case Card = 'card';
}
