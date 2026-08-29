<?php

namespace Azuriom\Plugin\Vouchers\Tests\Feature;

use Azuriom\Plugin\Vouchers\Models\Reward;
use Azuriom\Plugin\Vouchers\Models\Voucher;
use Azuriom\Plugin\Vouchers\Tests\TestCase;
use Illuminate\Support\ViewErrorBag;

class AdminVoucherViewTest extends TestCase
{
    public function test_the_admin_form_partial_renders_with_safe_default_values(): void
    {
        $this->app->make('view')->addNamespace('vouchers', dirname(__DIR__, 2).'/resources/views');

        $this->ensureGenerateRoute();

        $voucher = new Voucher([
            'name' => 'Test voucher',
            'code' => 'TEST-CODE-26',
            'is_enabled' => true,
            'requires_authentication' => true,
        ]);

        $html = view('vouchers::admin.codes._form', [
            'voucher' => $voucher,
            'formRewards' => [[
                'type' => Reward::TYPE_MONEY,
                'amount' => '10',
            ]],
            'shopAvailable' => false,
            'shopPackages' => collect(),
            'servers' => collect(),
            'internalRoles' => collect(),
            'errors' => new ViewErrorBag(),
        ])->render();

        $this->assertStringContainsString('id="nameInput"', $html);
        $this->assertStringContainsString('value="Test voucher"', $html);
        $this->assertStringContainsString('id="rewardsContainer"', $html);
    }

    public function test_admin_views_do_not_use_inline_raw_php_directives(): void
    {
        $viewPaths = glob(dirname(__DIR__, 2).'/resources/views/admin/codes/*.blade.php');

        $this->assertNotEmpty($viewPaths, 'No administrative voucher views were found.');

        foreach ($viewPaths as $viewPath) {
            $this->assertDoesNotMatchRegularExpression(
                '/@php\s*\(/',
                file_get_contents($viewPath),
                basename($viewPath).' uses an inline @php directive which is not portable across supported Laravel versions.',
            );
        }
    }

    public function test_integer_fields_use_digit_only_controls(): void
    {
        $settings = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/settings.blade.php');
        $form = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/codes/_form.blade.php');
        $reward = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/codes/_reward.blade.php');

        $this->assertStringContainsString('id="rateLimitInput"', $settings);
        $this->assertStringContainsString('id="globalLimitInput"', $form);
        $this->assertStringContainsString('id="userLimitInput"', $form);
        $this->assertSame(1, substr_count($settings, 'data-integer-input'));
        $this->assertSame(2, substr_count($form, 'data-integer-input'));
        $this->assertStringContainsString('data-integer-input data-active-required', $reward);
    }

    public function test_settings_include_the_optional_user_menu_switch(): void
    {
        $settings = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/settings.blade.php');

        $this->assertStringContainsString('id="vouchersUserMenuSwitch"', $settings);
        $this->assertStringContainsString('id="vouchersUserMenuIconInput"', $settings);
        $this->assertStringContainsString('id="vouchersUserMenuIconPreview"', $settings);
        $this->assertStringContainsString('name="user_menu"', $settings);
        $this->assertStringContainsString('name="user_menu_icon"', $settings);
        $this->assertStringContainsString('old(\'user_menu\', $showInUserMenu)', $settings);
    }

    public function test_an_unavailable_internal_role_snapshot_is_rendered_safely(): void
    {
        $this->app->make('view')->addNamespace('vouchers', dirname(__DIR__, 2).'/resources/views');

        $this->ensureGenerateRoute();

        $html = view('vouchers::admin.codes._form', [
            'voucher' => new Voucher([
                'name' => 'Role voucher',
                'code' => 'ROLE-CODE-26',
                'is_enabled' => true,
                'requires_authentication' => true,
            ]),
            'formRewards' => [[
                'type' => Reward::TYPE_INTERNAL_ROLE,
                'role_id' => 99,
                'role_name' => '<script>alert("voucher")</script>',
            ]],
            'shopAvailable' => false,
            'shopPackages' => collect(),
            'servers' => collect(),
            'internalRoles' => collect(),
            'errors' => new ViewErrorBag(),
        ])->render();

        $this->assertStringContainsString('value="99" selected', $html);
        $this->assertStringContainsString('&lt;script&gt;alert(&quot;voucher&quot;)&lt;/script&gt;', $html);
        $this->assertStringNotContainsString('<script>alert("voucher")</script>', $html);
    }

    private function ensureGenerateRoute(): void
    {
        $router = $this->app->make('router');

        if ($router->getRoutes()->getByName('vouchers.admin.codes.generate') === null) {
            $router->post('/admin/vouchers/codes/generate', fn () => null)
                ->name('vouchers.admin.codes.generate');

            $router->getRoutes()->refreshNameLookups();
        }

        $this->app->make('url')->setRoutes($router->getRoutes());
    }
}
