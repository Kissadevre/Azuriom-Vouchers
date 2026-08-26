@extends('admin.layouts.admin')

@section('title', trans('vouchers::admin.codes.title'))

@include('vouchers::admin._styles')

@section('content')
    <div class="card vouchers-admin-card mb-4">
        <div class="vouchers-admin-header">
            <div class="vouchers-admin-heading">
                <span class="vouchers-admin-icon"><i class="bi bi-ticket-perforated" aria-hidden="true"></i></span>
                <div>
                    <h2 class="h5 mb-1">{{ trans('vouchers::admin.codes.manage_title') }}</h2>
                    <p class="text-body-secondary mb-0">{{ trans('vouchers::admin.codes.description') }}</p>
                </div>
            </div>
            <a class="btn btn-primary" href="{{ route('vouchers.admin.codes.create') }}">
                <i class="bi bi-plus-lg" aria-hidden="true"></i> {{ trans('vouchers::admin.codes.create') }}
            </a>
        </div>

        <div class="card-body p-0">
            @if($vouchers->isEmpty())
                <div class="vouchers-empty-state" role="status">
                    <i class="bi bi-ticket-detailed" aria-hidden="true"></i>
                    <p class="text-body-secondary mb-3">{{ trans('vouchers::admin.codes.empty') }}</p>
                    <a class="btn btn-primary btn-sm" href="{{ route('vouchers.admin.codes.create') }}">
                        <i class="bi bi-plus-lg" aria-hidden="true"></i> {{ trans('vouchers::admin.codes.create') }}
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle vouchers-admin-table mb-0">
                        <thead>
                            <tr>
                                <th scope="col">{{ trans('vouchers::admin.fields.name') }}</th>
                                <th scope="col">{{ trans('vouchers::admin.fields.code') }}</th>
                                <th scope="col">{{ trans('vouchers::admin.fields.status') }}</th>
                                <th scope="col">{{ trans('vouchers::admin.fields.uses') }}</th>
                                <th scope="col">{{ trans('vouchers::admin.fields.rewards') }}</th>
                                <th scope="col" class="text-end">{{ trans('messages.fields.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vouchers as $voucher)
                                @php
                                    $status = $voucher->availabilityStatusAt(now());
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $voucher->name }}</td>
                                    <td><code class="vouchers-code">{{ $voucher->code }}</code></td>
                                    <td>
                                        <span class="badge bg-{{ trans('vouchers::admin.status.'.$status.'.color') }}">
                                            {{ trans('vouchers::admin.status.'.$status.'.label') }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $voucher->redemptions_count }} /
                                        {{ $voucher->max_redemptions ?? trans('vouchers::admin.unlimited') }}
                                    </td>
                                    <td>{{ $voucher->rewards_count }}</td>
                                    <td class="text-end text-nowrap">
                                        <div class="vouchers-action-group">
                                        <a href="{{ route('vouchers.admin.codes.edit', $voucher) }}" class="btn btn-outline-primary btn-sm" title="{{ trans('messages.actions.edit') }}" aria-label="{{ trans('messages.actions.edit') }}" data-bs-toggle="tooltip">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        @if($voucher->is_enabled)
                                            <form action="{{ route('vouchers.admin.codes.disable', $voucher) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-outline-warning btn-sm" title="{{ trans('vouchers::admin.actions.disable') }}" aria-label="{{ trans('vouchers::admin.actions.disable') }}" data-bs-toggle="tooltip">
                                                    <i class="bi bi-pause-circle"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('vouchers.admin.codes.destroy', $voucher) }}" class="btn btn-outline-danger btn-sm" title="{{ trans('messages.actions.delete') }}" aria-label="{{ trans('messages.actions.delete') }}" data-bs-toggle="tooltip" data-confirm="delete">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-3 border-top">
                    {{ $vouchers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
