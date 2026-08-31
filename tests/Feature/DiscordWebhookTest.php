<?php

namespace Azuriom\Plugin\Vouchers\Tests\Feature;

use Azuriom\Models\Setting;
use Azuriom\Plugin\Vouchers\Models\Redemption;
use Azuriom\Plugin\Vouchers\Models\Voucher;
use Azuriom\Plugin\Vouchers\Providers\VouchersServiceProvider;
use Azuriom\Plugin\Vouchers\Services\DiscordWebhookService;
use Azuriom\Plugin\Vouchers\Services\VoucherSettings;
use Azuriom\Plugin\Vouchers\Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DiscordWebhookTest extends TestCase
{
    public function test_only_official_discord_webhook_urls_are_accepted(): void
    {
        foreach ([
            'https://discord.com/api/webhooks/123456789/test-token',
            'https://canary.discord.com/api/v10/webhooks/123456789/test_token.with-dots',
            'https://discordapp.com/api/webhooks/123456789/test-token?wait=true',
        ] as $url) {
            $this->assertTrue(DiscordWebhookService::isValidUrl($url));
        }

        foreach ([
            'http://discord.com/api/webhooks/123456789/test-token',
            'https://example.com/api/webhooks/123456789/test-token',
            'https://discord.com.evil.test/api/webhooks/123456789/test-token',
            'https://attacker@discord.com/api/webhooks/123456789/test-token',
            'https://discord.com:8443/api/webhooks/123456789/test-token',
            'https://discord.com/login',
            'not-a-url',
        ] as $url) {
            $this->assertFalse(DiscordWebhookService::isValidUrl($url));
        }
    }

    public function test_redemption_notification_contains_the_requested_claim_details(): void
    {
        Http::fake(['discord.com/*' => Http::response(status: 204)]);
        Setting::updateSettings([
            VoucherSettings::DISCORD_WEBHOOK_ENABLED_KEY => true,
            VoucherSettings::DISCORD_WEBHOOK_URL_KEY => 'https://discord.com/api/webhooks/123456789/test-token',
        ]);

        $voucher = new Voucher(['name' => 'Launch campaign']);
        $redemption = new Redemption(['username' => 'Kissadere']);
        $redemption->created_at = now()->setTimestamp(1788051600);
        $redemption->setRelation('voucher', $voucher);

        app(DiscordWebhookService::class)->notifyRedemption($redemption);

        Http::assertSent(function ($request) {
            $embed = $request->data()['embeds'][0];

            return $request->url() === 'https://discord.com/api/webhooks/123456789/test-token'
                && $embed['fields'][0]['value'] === 'Kissadere'
                && $embed['fields'][1]['value'] === 'Launch campaign'
                && $embed['fields'][2]['value'] === '<t:1788051600:F>'
                && $embed['timestamp'] !== null;
        });
    }

    public function test_disabled_notifications_do_not_make_http_requests(): void
    {
        Http::fake();
        Setting::updateSettings(VoucherSettings::DISCORD_WEBHOOK_URL_KEY, 'https://discord.com/api/webhooks/123456789/test-token');

        $redemption = new Redemption(['username' => 'Kissadere']);
        $redemption->setRelation('voucher', new Voucher(['name' => 'Disabled webhook']));

        $this->assertNull(app(DiscordWebhookService::class)->notifyRedemption($redemption));
        Http::assertNothingSent();
    }

    public function test_manual_test_message_uses_the_administrator_provided_url(): void
    {
        Http::fake(['discord.com/*' => Http::response(status: 204)]);
        $url = 'https://discord.com/api/webhooks/123456789/test-token';

        app(DiscordWebhookService::class)->sendTest($url);

        Http::assertSent(fn ($request) => $request->url() === $url
            && data_get($request->data(), 'embeds.0.title') === trans('vouchers::messages.webhook.test_title'));
    }

    public function test_webhook_url_is_encrypted_in_the_settings_table(): void
    {
        $url = 'https://discord.com/api/webhooks/123456789/secret-token';
        (new VouchersServiceProvider($this->app))->register();

        Setting::updateSettings(VoucherSettings::DISCORD_WEBHOOK_URL_KEY, $url);

        $stored = DB::table('settings')
            ->where('name', VoucherSettings::DISCORD_WEBHOOK_URL_KEY)
            ->value('value');

        $this->assertNotSame($url, $stored);
        $this->assertSame($url, setting(VoucherSettings::DISCORD_WEBHOOK_URL_KEY));
    }
}
