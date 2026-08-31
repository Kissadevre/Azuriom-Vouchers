<?php

namespace Azuriom\Plugin\Vouchers\Services;

use Azuriom\Plugin\Vouchers\Models\Redemption;
use Azuriom\Support\Discord\DiscordWebhook;
use Azuriom\Support\Discord\Embed;
use Illuminate\Http\Client\Response;

class DiscordWebhookService
{
    private const ALLOWED_HOSTS = [
        'discord.com',
        'ptb.discord.com',
        'canary.discord.com',
        'discordapp.com',
        'ptb.discordapp.com',
        'canary.discordapp.com',
    ];

    public function __construct(private readonly VoucherSettings $settings)
    {
    }

    /**
     * Notify Discord about a newly persisted voucher claim when enabled.
     */
    public function notifyRedemption(Redemption $redemption): ?Response
    {
        $url = $this->settings->discordWebhookUrl();

        if (! $this->settings->discordWebhookEnabled() || $url === null || ! self::isValidUrl($url)) {
            return null;
        }

        $redemption->loadMissing('voucher');
        $claimedAt = $redemption->created_at ?? now();
        $embed = Embed::create()
            ->title(trans('vouchers::messages.webhook.redemption_title'))
            ->color('#5865F2')
            ->addField(trans('vouchers::messages.webhook.user'), $redemption->username, true)
            ->addField(trans('vouchers::messages.webhook.voucher'), $redemption->voucher->name, true)
            ->addField(
                trans('vouchers::messages.webhook.redeemed_at'),
                '<t:'.$claimedAt->getTimestamp().':F>',
            )
            ->timestamp($claimedAt);

        return DiscordWebhook::create()
            ->username('Vouchers')
            ->addEmbed($embed)
            ->send($url);
    }

    /**
     * Send a harmless message to verify an administrator-provided endpoint.
     */
    public function sendTest(string $url): Response
    {
        $embed = Embed::create()
            ->title(trans('vouchers::messages.webhook.test_title'))
            ->description(trans('vouchers::messages.webhook.test_description'))
            ->color('#5865F2')
            ->timestamp(now());

        return DiscordWebhook::create()
            ->username('Vouchers')
            ->addEmbed($embed)
            ->send($url);
    }

    /**
     * Restrict outgoing requests to official Discord webhook endpoints.
     */
    public static function isValidUrl(mixed $url): bool
    {
        if (! is_string($url) || filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');
        $path = $parts['path'] ?? '';

        return ($parts['scheme'] ?? '') === 'https'
            && in_array($host, self::ALLOWED_HOSTS, true)
            && ! isset($parts['user'])
            && ! isset($parts['pass'])
            && ! isset($parts['port'])
            && preg_match('#^/api(?:/v[0-9]+)?/webhooks/[0-9]+/[A-Za-z0-9._-]+/?$#D', $path) === 1;
    }
}
