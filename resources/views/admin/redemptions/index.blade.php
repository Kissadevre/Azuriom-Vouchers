@extends('admin.layouts.admin')

@section('title', trans('vouchers::admin.redemptions.title'))

@include('vouchers::admin._styles')

@section('content')
    <div class="card vouchers-admin-card mb-4">
        <div class="vouchers-admin-header">
            <div class="vouchers-admin-heading">
                <span class="vouchers-admin-icon"><i class="bi bi-clock-history" aria-hidden="true"></i></span>
                <div>
                    <h2 class="h5 mb-1">{{ trans('vouchers::admin.redemptions.activity_title') }}</h2>
                    <p class="text-body-secondary mb-0">{{ trans('vouchers::admin.redemptions.description') }}</p>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if($redemptions->isEmpty())
                <div class="vouchers-empty-state" role="status">
                    <i class="bi bi-clock-history" aria-hidden="true"></i>
                    <p class="text-body-secondary mb-0">{{ trans('vouchers::admin.redemptions.empty') }}</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle vouchers-admin-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{ trans('vouchers::admin.redemptions.reference') }}</th>
                                <th scope="col">{{ trans('vouchers::admin.redemptions.voucher') }}</th>
                                <th scope="col">{{ trans('vouchers::admin.redemptions.recipient') }}</th>
                                <th scope="col">{{ trans('vouchers::admin.redemptions.redeemer') }}</th>
                                <th scope="col">{{ trans('vouchers::admin.redemptions.ip_address') }}</th>
                                <th scope="col">{{ trans('vouchers::admin.fields.status') }}</th>
                                <th scope="col">{{ trans('vouchers::admin.fields.rewards') }}</th>
                                <th scope="col">{{ trans('vouchers::admin.redemptions.date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($redemptions as $redemption)
                                <tr>
                                    <td><code class="vouchers-code">{{ strtoupper(substr($redemption->uuid, 0, 8)) }}</code></td>
                                    <td class="fw-semibold">{{ $redemption->voucher->name }}</td>
                                    <td><i class="bi bi-person me-1 text-body-secondary" aria-hidden="true"></i>{{ $redemption->user?->name ?? $redemption->username }}</td>
                                    <td>
                                        @if($redemption->redeemer !== null)
                                            {{ $redemption->redeemer->name }}
                                        @else
                                            <span class="text-muted">{{ trans('vouchers::admin.redemptions.guest') }}</span>
                                        @endif
                                    </td>
                                    <td><code class="text-body-secondary">{{ $redemption->ip_address ?? '—' }}</code></td>
                                    <td>
                                        <span class="badge bg-{{ trans('vouchers::admin.redemption_status.'.$redemption->status.'.color') }}">
                                            {{ trans('vouchers::admin.redemption_status.'.$redemption->status.'.label') }}
                                        </span>
                                    </td>
                                    <td>{{ $redemption->executions_count }}</td>
                                    <td class="text-nowrap">{{ format_date($redemption->created_at, true) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-3 border-top">
                    {{ $redemptions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
