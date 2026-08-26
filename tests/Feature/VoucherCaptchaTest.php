<?php

namespace Azuriom\Plugin\Vouchers\Tests\Feature;

use Azuriom\Plugin\Vouchers\Tests\TestCase;

class VoucherCaptchaTest extends TestCase
{
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
    }
}
