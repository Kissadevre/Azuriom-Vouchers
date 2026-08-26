@extends('admin.layouts.admin')

@section('title', trans('vouchers::admin.settings.title'))

@include('vouchers::admin._styles')
@include('vouchers::admin._integer-input')

@section('content')
    <div class="card vouchers-admin-card mb-4">
        <div class="vouchers-admin-header">
            <div class="vouchers-admin-heading">
                <span class="vouchers-admin-icon"><i class="bi bi-shield-check" aria-hidden="true"></i></span>
                <div>
                    <h2 class="h5 mb-1">{{ trans('vouchers::admin.settings.security_title') }}</h2>
                    <p class="text-body-secondary mb-0">{{ trans('vouchers::admin.settings.security_description') }}</p>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('vouchers.admin.settings') }}" method="POST">
                @csrf

                <div class="vouchers-admin-section mb-4">
                    <div class="form-check form-switch">
                        <input type="hidden" name="enabled" value="0">
                        <input type="checkbox" class="form-check-input" id="vouchersEnabledSwitch" name="enabled" value="1" @checked(old('enabled', $vouchersEnabled))>
                        <label class="form-check-label fw-semibold" for="vouchersEnabledSwitch">{{ trans('vouchers::admin.settings.enabled') }}</label>
                        <div class="form-text">{{ trans('vouchers::admin.settings.enabled_help') }}</div>
                    </div>
                </div>

                <div class="vouchers-admin-section mb-4">
                    <div class="row align-items-center g-3">
                        <div class="col-lg-5">
                            <label class="form-label fw-semibold mb-1" for="rateLimitInput">{{ trans('vouchers::admin.settings.rate_limit') }}</label>
                            <div class="form-text mt-0">{{ trans('vouchers::admin.settings.rate_limit_help') }}</div>
                        </div>
                        <div class="col-lg-7">
                            <div class="input-group @error('rate_limit') has-validation @enderror">
                                <input type="text" inputmode="numeric" pattern="[0-9]+" maxlength="4" class="form-control @error('rate_limit') is-invalid @enderror" id="rateLimitInput" name="rate_limit" value="{{ old('rate_limit', $rateLimit) }}" data-integer-input required>
                                <span class="input-group-text">{{ trans('vouchers::admin.settings.attempts_per_minute') }}</span>
                                @error('rate_limit')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg" aria-hidden="true"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>
@endsection
