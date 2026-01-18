<?php

declare(strict_types=1);

namespace App\Modules\Billing\Auth\Permissions;

final class BillingPermissions
{
    public const ACCESS = 'billing.access';

    public const SALES_VIEW   = 'billing.sales.view';
    public const SALES_CREATE = 'billing.sales.create';
    public const SALES_EDIT   = 'billing.sales.edit';
    public const SALES_CANCEL = 'billing.sales.cancel';

    public const REPORTS_VIEW = 'billing.reports.view';
    public const SETTINGS_MANAGE = 'billing.settings.manage';

    /**
     * @return array<int, string>
     */
    public static function all(): array
    {
        return [
            self::ACCESS,
            self::SALES_VIEW,
            self::SALES_CREATE,
            self::SALES_EDIT,
            self::SALES_CANCEL,
            self::REPORTS_VIEW,
            self::SETTINGS_MANAGE,
        ];
    }
}
