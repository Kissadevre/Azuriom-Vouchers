<?php

namespace Azuriom\Plugin\Vouchers\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Permission;
use Azuriom\Plugin\Vouchers\Commands\ProcessDeliveriesCommand;
use Azuriom\Plugin\Vouchers\Models\Reward;
use Azuriom\Plugin\Vouchers\Models\Voucher;
use Azuriom\Plugin\Vouchers\Services\VoucherSettings;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class VouchersServiceProvider extends BasePluginServiceProvider
{
    /**
     * Bootstrap the plugin services.
     */
    public function boot(): void
    {
        $this->loadViews();
        $this->loadTranslations();
        $this->loadMigrations();
        $this->registerRouteDescriptions();
        $this->registerAdminNavigation();
        $this->registerUserNavigation();
        $this->registerSchedule();
        $this->registerRateLimiter();

        $this->commands(ProcessDeliveriesCommand::class);

        Permission::registerPermissions([
            'vouchers.admin' => 'vouchers::admin.permission',
        ]);

        ActionLog::registerLogModels([
            Voucher::class,
            Reward::class,
        ], 'vouchers::admin.logs');

        ActionLog::registerLogs('vouchers.settings.updated', [
            'icon' => 'ticket-perforated',
            'color' => 'info',
            'message' => 'vouchers::admin.logs.settings',
        ]);
    }

    /**
     * Process deferred rewards and close abandoned delivery claims.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('vouchers:deliveries')
            ->everyFiveMinutes()
            ->withoutOverlapping(15);
    }

    /**
     * Register the configurable public redemption rate limiter.
     */
    protected function registerRateLimiter(): void
    {
        RateLimiter::for('vouchers-redeem', function (Request $request) {
            $attempts = $this->app->make(VoucherSettings::class)->rateLimit();

            return Limit::perMinute($attempts)
                ->by('vouchers-redeem|'.$request->ip())
                ->response(fn (Request $request, array $headers) => to_route('vouchers.index')
                    ->withErrors(['code' => trans('vouchers::messages.errors.too_many_attempts')])
                    ->withInput($request->only('username'))
                    ->withHeaders($headers));
        });
    }

    /**
     * Return the routes that can be added to the site navigation.
     *
     * @return array<string, string>
     */
    protected function routeDescriptions(): array
    {
        return [
            'vouchers.index' => trans('vouchers::messages.title'),
        ];
    }

    /**
     * Return the plugin entries for the administration navigation.
     *
     * @return array<string, array<string, string>>
     */
    protected function adminNavigation(): array
    {
        return [
            'vouchers' => [
                'name' => trans('vouchers::admin.title'),
                'type' => 'dropdown',
                'icon' => 'bi bi-ticket-perforated',
                'permission' => 'vouchers.admin',
                'route' => 'vouchers.admin.*',
                'items' => [
                    'vouchers.admin.settings' => trans('vouchers::admin.nav.settings'),
                    'vouchers.admin.codes.index' => trans('vouchers::admin.nav.codes'),
                    'vouchers.admin.redemptions.index' => trans('vouchers::admin.nav.redemptions'),
                ],
            ],
        ];
    }

    /**
     * Return the optional entry shown in the authenticated user menu.
     *
     * @return array<string, array<string, string>>
     */
    protected function userNavigation(): array
    {
        if (! $this->app->make(VoucherSettings::class)->showInUserMenu()) {
            return [];
        }

        return [
            'vouchers' => [
                'route' => 'vouchers.index',
                'name' => trans('vouchers::messages.nav.vouchers'),
                'icon' => 'bi '.$this->app->make(VoucherSettings::class)->userMenuIcon(),
            ],
        ];
    }
}
