<?php

namespace Azuriom\Plugin\Vouchers\Providers;

use Azuriom\Extensions\Plugin\BaseRouteServiceProvider;
use Azuriom\Plugin\Vouchers\Middleware\LogVouchersRequest;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    /**
     * Define the plugin routes.
     */
    public function loadRoutes(): void
    {
        Route::middleware(['web', LogVouchersRequest::class])
            ->prefix($this->plugin->id)
            ->name($this->plugin->id.'.')
            ->group(plugin_path($this->plugin->id.'/routes/web.php'));

        Route::middleware(['admin-access', LogVouchersRequest::class])
            ->prefix('admin/'.$this->plugin->id)
            ->name($this->plugin->id.'.admin.')
            ->group(plugin_path($this->plugin->id.'/routes/admin.php'));
    }
}
