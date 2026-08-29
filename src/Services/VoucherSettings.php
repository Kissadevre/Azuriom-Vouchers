<?php

namespace Azuriom\Plugin\Vouchers\Services;

class VoucherSettings
{
    public const ENABLED_KEY = 'vouchers.enabled';

    public const RATE_LIMIT_KEY = 'vouchers.rate_limit';

    public const USER_MENU_KEY = 'vouchers.user_menu';

    public const USER_MENU_ICON_KEY = 'vouchers.user_menu_icon';

    public const DISCORD_WEBHOOK_ENABLED_KEY = 'vouchers.discord_webhook.enabled';

    public const DISCORD_WEBHOOK_URL_KEY = 'vouchers.discord_webhook.url';

    public const DEFAULT_USER_MENU_ICON = 'bi-ticket-perforated';

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

    /**
     * Get the validated Bootstrap Icon name used by the user menu shortcut.
     */
    public function userMenuIcon(): string
    {
        $icon = setting(self::USER_MENU_ICON_KEY, self::DEFAULT_USER_MENU_ICON);

        if (! is_string($icon) || preg_match('/^bi-[a-z0-9]+(?:-[a-z0-9]+)*$/D', $icon) !== 1) {
            return self::DEFAULT_USER_MENU_ICON;
        }

        return $icon;
    }

    /**
     * Determine whether successful voucher claims should notify Discord.
     */
    public function discordWebhookEnabled(): bool
    {
        return filter_var(setting(self::DISCORD_WEBHOOK_ENABLED_KEY, false), FILTER_VALIDATE_BOOL);
    }

    /**
     * Get the configured Discord webhook URL, when present.
     */
    public function discordWebhookUrl(): ?string
    {
        $url = setting(self::DISCORD_WEBHOOK_URL_KEY);

        return is_string($url) && trim($url) !== '' ? trim($url) : null;
    }
}
