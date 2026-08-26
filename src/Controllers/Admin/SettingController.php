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
        ]);
    }

    /**
     * Update the voucher settings.
     */
    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'enabled' => ['required', 'boolean'],
            'rate_limit' => ['required', 'regex:/^[0-9]+$/D', 'integer', 'min:1', 'max:1000'],
        ]);

        Setting::updateSettings([
            VoucherSettings::ENABLED_KEY => (bool) $validated['enabled'],
            VoucherSettings::RATE_LIMIT_KEY => (int) $validated['rate_limit'],
        ]);
        ActionLog::log('vouchers.settings.updated');

        return to_route('vouchers.admin.settings')
            ->with('success', trans('vouchers::admin.settings.updated'));
    }
}
