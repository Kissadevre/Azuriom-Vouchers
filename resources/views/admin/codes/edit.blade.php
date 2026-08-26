@extends('admin.layouts.admin')

@section('title')
    {{ trans('vouchers::admin.codes.edit', ['voucher' => $voucher->name]) }}
@endsection

@include('vouchers::admin._styles')

@section('content')
    @php
        $revisionValue = old('revision', $voucher->revision);
        $revisionValue = is_scalar($revisionValue) ? $revisionValue : '';
    @endphp
    <form action="{{ route('vouchers.admin.codes.update', $voucher) }}" method="POST">
        @method('PUT')
        <input type="hidden" name="revision" value="{{ $revisionValue }}">

        <div class="card vouchers-admin-card mb-4">
            <div class="vouchers-admin-header">
                <div class="vouchers-admin-heading">
                    <span class="vouchers-admin-icon"><i class="bi bi-pencil-square" aria-hidden="true"></i></span>
                    <div>
                        <h2 class="h5 mb-1">{{ trans('vouchers::admin.codes.edit', ['voucher' => $voucher->name]) }}</h2>
                        <p class="text-body-secondary mb-0">{{ trans('vouchers::admin.codes.form_description') }}</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                @error('revision')
                    <div class="alert alert-warning" role="alert">{{ $message }}</div>
                @enderror

                @include('vouchers::admin.codes._form')

                <button type="submit" class="btn btn-primary mt-2">
                    <i class="bi bi-check-lg" aria-hidden="true"></i> {{ trans('messages.actions.save') }}
                </button>
            </div>
        </div>
    </form>
@endsection
