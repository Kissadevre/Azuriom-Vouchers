<?php

namespace Azuriom\Plugin\Vouchers\Services;

class VoucherSettings
{
    public const ENABLED_KEY = 'vouchers.enabled';

    public const RATE_LIMIT_KEY = 'vouchers.rate_limit';

    public const USER_MENU_KEY = 'vouchers.user_menu';

    public const DEFAULT_RATE_LIMIT = 10;

    /**
     * Determine whether public voucher redemption is enabled globally.
     */
    public function enabled(): bool
    {
        return filter_var(setting(self::ENABLED_KEY, true), FILTER_VALIDATE_BOOL);
    }

    /**
     * Get the maximum number of redemption attempts allowed per minute.
     */
    public function rateLimit(): int
    {
        return max(1, min(1000, (int) setting(self::RATE_LIMIT_KEY, self::DEFAULT_RATE_LIMIT)));
    }

    /**
     * Determine whether the public voucher page is shown in the authenticated user menu.
     */
    public function showInUserMenu(): bool
    {
        return filter_var(setting(self::USER_MENU_KEY, false), FILTER_VALIDATE_BOOL);
    }
}
