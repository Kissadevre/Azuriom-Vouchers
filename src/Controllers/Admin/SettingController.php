<?php

namespace Azuriom\Plugin\Vouchers\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Setting;
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
        ]);

        Setting::updateSettings([
            VoucherSettings::ENABLED_KEY => (bool) $validated['enabled'],
            VoucherSettings::USER_MENU_KEY => (bool) $validated['user_menu'],
            VoucherSettings::USER_MENU_ICON_KEY => $validated['user_menu_icon'],
            VoucherSettings::RATE_LIMIT_KEY => (int) $validated['rate_limit'],
        ]);
        ActionLog::log('vouchers.settings.updated');

        return to_route('vouchers.admin.settings')
            ->with('success', trans('vouchers::admin.settings.updated'));
    }
}
