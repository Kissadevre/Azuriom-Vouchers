<?php

namespace Azuriom\Plugin\Vouchers\Tests\Feature;

use Azuriom\Plugin\Vouchers\Controllers\VoucherController;
use Azuriom\Plugin\Vouchers\Services\VoucherSettings;
use Azuriom\Plugin\Vouchers\Tests\TestCase;
use Illuminate\Http\Request;

class VoucherCaptchaTest extends TestCase
{
    public function test_url_code_is_prefilled_without_triggering_a_redemption(): void
    {
        $this->app->make('view')->addNamespace('vouchers', dirname(__DIR__, 2).'/resources/views');

        $controller = app(VoucherController::class);
        $view = $controller->index(
            Request::create('/vouchers?code=test-1234', 'GET'),
            app(VoucherSettings::class),
        );

        $this->assertSame('TEST-1234', $view->getData()['initialCode']);

        foreach (['invalid_code', 'TOO-LONG-CODE-1'] as $code) {
            $view = $controller->index(
                Request::create('/vouchers?code='.urlencode($code), 'GET'),
                app(VoucherSettings::class),
            );

            $this->assertSame('', $view->getData()['initialCode']);
        }

        $arrayView = $controller->index(
            Request::create('/vouchers?code[]=TEST-1234', 'GET'),
            app(VoucherSettings::class),
        );

        $this->assertSame('', $arrayView->getData()['initialCode']);
    }

    public function test_redeem_route_uses_azuriom_captcha_middleware(): void
    {
        $router = $this->app->make('router');

        $router->prefix('vouchers')
            ->name('vouchers.')
            ->group(dirname(__DIR__, 2).'/routes/web.php');
        $router->getRoutes()->refreshNameLookups();

        $route = $router->getRoutes()->getByName('vouchers.redeem');

        $this->assertNotNull($route);
        $this->assertContains('throttle:vouchers-redeem', $route->gatherMiddleware());
        $this->assertContains('captcha', $route->gatherMiddleware());
    }

    public function test_redeem_form_uses_the_shared_azuriom_captcha_element(): void
    {
        $view = file_get_contents(dirname(__DIR__, 2).'/resources/views/index.blade.php');

        $this->assertStringContainsString('id="captcha-form"', $view);
        $this->assertStringContainsString("@include('elements.captcha', ['center' => true])", $view);
        $this->assertStringContainsString('autocomplete="one-time-code"', $view);
        $this->assertStringContainsString('aria-describedby="codeHelp"', $view);
        $this->assertStringContainsString('value="{{ old(\'code\', $initialCode) }}"', $view);
        $this->assertStringContainsString('maxlength="14"', $view);
    }
}
