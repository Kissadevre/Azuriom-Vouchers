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
                    <div class="form-check form-switch">
                        <input type="hidden" name="user_menu" value="0">
                        <input type="checkbox" class="form-check-input" id="vouchersUserMenuSwitch" name="user_menu" value="1" @checked(old('user_menu', $showInUserMenu))>
                        <label class="form-check-label fw-semibold" for="vouchersUserMenuSwitch">{{ trans('vouchers::admin.settings.user_menu') }}</label>
                        <div class="form-text">{{ trans('vouchers::admin.settings.user_menu_help') }}</div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label fw-semibold" for="vouchersUserMenuIconInput">{{ trans('vouchers::admin.settings.user_menu_icon') }}</label>
                        <div class="input-group @error('user_menu_icon') has-validation @enderror">
                            <span class="input-group-text" aria-hidden="true">
                                <i class="bi {{ old('user_menu_icon', $userMenuIcon) }}" id="vouchersUserMenuIconPreview"></i>
                            </span>
                            <input type="text" class="form-control font-monospace @error('user_menu_icon') is-invalid @enderror" id="vouchersUserMenuIconInput" name="user_menu_icon" value="{{ old('user_menu_icon', $userMenuIcon) }}" maxlength="64" pattern="bi-[a-z0-9]+(?:-[a-z0-9]+)*" placeholder="bi-ticket-perforated" autocomplete="off" required>
                            @error('user_menu_icon')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-text">{!! trans('vouchers::admin.settings.user_menu_icon_help') !!}</div>
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

                <div class="vouchers-admin-section mb-4">
                    <div class="form-check form-switch mb-3">
                        <input type="hidden" name="discord_webhook_enabled" value="0">
                        <input type="checkbox" class="form-check-input" id="discordWebhookEnabledSwitch" name="discord_webhook_enabled" value="1" @checked(old('discord_webhook_enabled', $discordWebhookEnabled))>
                        <label class="form-check-label fw-semibold" for="discordWebhookEnabledSwitch">{{ trans('vouchers::admin.settings.discord_webhook_enabled') }}</label>
                        <div class="form-text">{{ trans('vouchers::admin.settings.discord_webhook_enabled_help') }}</div>
                    </div>

                    <label class="form-label fw-semibold" for="discordWebhookUrlInput">{{ trans('vouchers::admin.settings.discord_webhook_url') }}</label>
                    <div class="input-group @error('discord_webhook_url') has-validation @enderror">
                        <span class="input-group-text" aria-hidden="true"><i class="bi bi-discord"></i></span>
                        <input type="url" class="form-control @error('discord_webhook_url') is-invalid @enderror" id="discordWebhookUrlInput" name="discord_webhook_url" value="{{ old('discord_webhook_url', $discordWebhookUrl) }}" maxlength="255" placeholder="https://discord.com/api/webhooks/.../..." autocomplete="off">
                        <button type="submit" class="btn btn-outline-secondary" formaction="{{ route('vouchers.admin.settings.webhook.test') }}" formmethod="POST">
                            <i class="bi bi-send-check" aria-hidden="true"></i> {{ trans('vouchers::admin.settings.discord_webhook_test') }}
                        </button>
                        @error('discord_webhook_url')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-text">{{ trans('vouchers::admin.settings.discord_webhook_url_help') }}</div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg" aria-hidden="true"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>
@endsection

@push('footer-scripts')
    <script>
        document.getElementById('vouchersUserMenuIconInput').addEventListener('input', function () {
            const icon = this.value.trim().toLowerCase();
            const preview = document.getElementById('vouchersUserMenuIconPreview');

            preview.className = /^bi-[a-z0-9]+(?:-[a-z0-9]+)*$/.test(icon)
                ? `bi ${icon}`
                : 'bi bi-question-circle';
        });
    </script>
@endpush
