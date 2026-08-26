@extends('admin.layouts.admin')

@section('title', trans('vouchers::admin.settings.title'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('vouchers.admin.settings') }}" method="POST">
                @csrf

                <div class="mb-4 form-check form-switch">
                    <input type="hidden" name="enabled" value="0">
                    <input type="checkbox" class="form-check-input" id="vouchersEnabledSwitch" name="enabled" value="1" @checked(old('enabled', $vouchersEnabled))>
                    <label class="form-check-label" for="vouchersEnabledSwitch">{{ trans('vouchers::admin.settings.enabled') }}</label>
                    <div class="form-text">{{ trans('vouchers::admin.settings.enabled_help') }}</div>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="rateLimitInput">{{ trans('vouchers::admin.settings.rate_limit') }}</label>
                    <div class="input-group @error('rate_limit') has-validation @enderror">
                        <input type="number" min="1" max="1000" class="form-control @error('rate_limit') is-invalid @enderror" id="rateLimitInput" name="rate_limit" value="{{ old('rate_limit', $rateLimit) }}" required>
                        <span class="input-group-text">{{ trans('vouchers::admin.settings.attempts_per_minute') }}</span>
                        @error('rate_limit')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-text">{{ trans('vouchers::admin.settings.rate_limit_help') }}</div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>
@endsection
