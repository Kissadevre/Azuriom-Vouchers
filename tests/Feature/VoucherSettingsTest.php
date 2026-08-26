<?php

namespace Azuriom\Plugin\Vouchers\Tests\Feature;

use Azuriom\Models\Setting;
use Azuriom\Plugin\Vouchers\Providers\VouchersServiceProvider;
use Azuriom\Plugin\Vouchers\Services\VoucherSettings;
use Azuriom\Plugin\Vouchers\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class VoucherSettingsTest extends TestCase
{
    public function test_settings_have_safe_defaults_and_persist_changes(): void
    {
        $settings = app(VoucherSettings::class);

        $this->assertTrue($settings->enabled());
        $this->assertSame(10, $settings->rateLimit());

        Setting::updateSettings([
            VoucherSettings::ENABLED_KEY => false,
            VoucherSettings::RATE_LIMIT_KEY => 7,
        ]);

        $this->assertFalse($settings->enabled());
        $this->assertSame(7, $settings->rateLimit());
    }

    public function test_named_rate_limiter_uses_current_setting_and_ip_address(): void
    {
        Setting::updateSettings(VoucherSettings::RATE_LIMIT_KEY, 4);

        if (RateLimiter::limiter('vouchers-redeem') === null) {
            $provider = new VouchersServiceProvider($this->app);
            (new \ReflectionMethod($provider, 'registerRateLimiter'))->invoke($provider);
        }

        $limiter = RateLimiter::limiter('vouchers-redeem');
        $request = Request::create('/vouchers/redeem', 'POST', server: [
            'REMOTE_ADDR' => '192.0.2.10',
        ]);
        $limit = $limiter($request);

        $this->assertSame(4, $limit->maxAttempts);
        $this->assertSame(60, $limit->decaySeconds);
        $this->assertSame('vouchers-redeem|192.0.2.10', $limit->key);
    }

    public function test_invalid_stored_rate_limits_are_clamped_defensively(): void
    {
        $settings = app(VoucherSettings::class);

        Setting::updateSettings(VoucherSettings::RATE_LIMIT_KEY, 0);
        $this->assertSame(1, $settings->rateLimit());

        Setting::updateSettings(VoucherSettings::RATE_LIMIT_KEY, 5000);
        $this->assertSame(1000, $settings->rateLimit());
    }
}
