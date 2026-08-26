@extends('admin.layouts.admin')

@section('title', trans('vouchers::admin.redemptions.title'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <p class="text-muted">{{ trans('vouchers::admin.redemptions.description') }}</p>

            @if($redemptions->isEmpty())
                <div class="alert alert-info mb-0" role="alert">
                    <i class="bi bi-info-circle"></i> {{ trans('vouchers::admin.redemptions.empty') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
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
                                    <td><code>{{ strtoupper(substr($redemption->uuid, 0, 8)) }}</code></td>
                                    <td>{{ $redemption->voucher->name }}</td>
                                    <td>{{ $redemption->user?->name ?? $redemption->username }}</td>
                                    <td>
                                        @if($redemption->redeemer !== null)
                                            {{ $redemption->redeemer->name }}
                                        @else
                                            <span class="text-muted">{{ trans('vouchers::admin.redemptions.guest') }}</span>
                                        @endif
                                    </td>
                                    <td><code>{{ $redemption->ip_address ?? '—' }}</code></td>
                                    <td>
                                        <span class="badge bg-{{ trans('vouchers::admin.redemption_status.'.$redemption->status.'.color') }}">
                                            {{ trans('vouchers::admin.redemption_status.'.$redemption->status.'.label') }}
                                        </span>
                                    </td>
                                    <td>{{ $redemption->executions_count }}</td>
                                    <td>{{ format_date($redemption->created_at, true) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $redemptions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
