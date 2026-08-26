<?php

namespace Azuriom\Plugin\Vouchers\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Vouchers\Models\Redemption;
use Illuminate\Contracts\View\View;

class RedemptionController extends Controller
{
    /**
     * Display the voucher redemption ledger.
     */
    public function index(): View
    {
        $redemptions = Redemption::query()
            ->with(['voucher:id,name', 'user:id,name,role_id', 'redeemer:id,name,role_id'])
            ->withCount('executions')
            ->latest()
            ->paginate();

        return view('vouchers::admin.redemptions.index', [
            'redemptions' => $redemptions,
        ]);
    }
}
