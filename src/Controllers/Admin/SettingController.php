<?php

namespace Azuriom\Plugin\Vouchers\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Setting;
use Azuriom\Plugin\Vouchers\Services\DiscordWebhookService;
use Azuriom\Plugin\Vouchers\Services\VoucherSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the voucher settings.
     */
    public function show(VoucherSettings $settings): View
    {
        return view('vouchers::admin.settings', [
            'vouchersEnabled' => $settings->enabled(),
            'rateLimit' => $settings->rateLimit(),
            'showInUserMenu' => $settings->showInUserMenu(),
            'userMenuIcon' => $settings->userMenuIcon(),
            'discordWebhookEnabled' => $settings->discordWebhookEnabled(),
            'discordWebhookUrl' => $settings->discordWebhookUrl(),
        ]);
    }

    /**
     * Update the voucher settings.
     */
    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'enabled' => ['required', 'boolean'],
            'user_menu' => ['required', 'boolean'],
            'user_menu_icon' => ['required', 'string', 'max:64', 'regex:/^bi-[a-z0-9]+(?:-[a-z0-9]+)*$/D'],
            'rate_limit' => ['required', 'regex:/^[0-9]+$/D', 'integer', 'min:1', 'max:1000'],
            'discord_webhook_enabled' => ['required', 'boolean'],
            'discord_webhook_url' => [
                'nullable',
                'required_if:discord_webhook_enabled,1',
                'string',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($value !== null && $value !== '' && ! DiscordWebhookService::isValidUrl($value)) {
                        $fail(trans('vouchers::admin.settings.discord_webhook_url_invalid'));
                    }
                },
            ],
        ]);

        Setting::updateSettings([
            VoucherSettings::ENABLED_KEY => (bool) $validated['enabled'],
            VoucherSettings::USER_MENU_KEY => (bool) $validated['user_menu'],
            VoucherSettings::USER_MENU_ICON_KEY => $validated['user_menu_icon'],
            VoucherSettings::RATE_LIMIT_KEY => (int) $validated['rate_limit'],
            VoucherSettings::DISCORD_WEBHOOK_ENABLED_KEY => (bool) $validated['discord_webhook_enabled'],
            VoucherSettings::DISCORD_WEBHOOK_URL_KEY => filled($validated['discord_webhook_url'] ?? null)
                ? trim($validated['discord_webhook_url'])
                : null,
        ]);
        ActionLog::log('vouchers.settings.updated');

        return to_route('vouchers.admin.settings')
            ->with('success', trans('vouchers::admin.settings.updated'));
    }

    /**
     * Verify an administrator-provided Discord webhook without saving settings.
     */
    public function testWebhook(Request $request, DiscordWebhookService $webhook): RedirectResponse
    {
        $validated = $request->validate([
            'discord_webhook_url' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if (! DiscordWebhookService::isValidUrl($value)) {
                        $fail(trans('vouchers::admin.settings.discord_webhook_url_invalid'));
                    }
                },
            ],
        ]);

        try {
            $webhook->sendTest(trim($validated['discord_webhook_url']));
        } catch (\Throwable $exception) {
            report($exception);

            return to_route('vouchers.admin.settings')
                ->withInput()
                ->with('error', trans('vouchers::admin.settings.discord_webhook_test_failed'));
        }

        return to_route('vouchers.admin.settings')
            ->withInput()
            ->with('success', trans('vouchers::admin.settings.discord_webhook_test_sent'));
    }
}
