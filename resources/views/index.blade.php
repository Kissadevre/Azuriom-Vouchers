@extends('layouts.app')

@section('title', trans('vouchers::messages.title'))

@push('styles')
    <style>
        .voucher-redeem-card {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(var(--bs-primary-rgb), .16);
            border-radius: 1.25rem;
        }

        .voucher-redeem-card::before {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            height: .25rem;
            content: '';
            background: var(--bs-primary);
        }

        .voucher-redeem-icon {
            display: inline-flex;
            width: 3.25rem;
            height: 3.25rem;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            border-radius: 1rem;
            color: var(--bs-primary);
            background: rgba(var(--bs-primary-rgb), .12);
            font-size: 1.5rem;
        }

        .voucher-input-wrap {
            position: relative;
        }

        .voucher-input-wrap > .bi {
            position: absolute;
            z-index: 4;
            top: 50%;
            left: 1rem;
            color: var(--bs-secondary-color);
            transform: translateY(-50%);
            pointer-events: none;
        }

        .voucher-input-wrap > .form-control {
            min-height: 3.5rem;
            padding-left: 2.75rem;
            border-radius: .85rem;
        }

        .voucher-code-input {
            letter-spacing: .08em;
        }

        .voucher-recipient {
            display: flex;
            align-items: center;
            gap: .75rem;
            border-radius: .85rem;
        }

        .voucher-submit {
            min-height: 3.25rem;
            border-radius: .85rem;
        }

        @media (max-width: 575.98px) {
            .voucher-redeem-card {
                border-radius: 1rem;
            }

            .voucher-redeem-icon {
                width: 2.75rem;
                height: 2.75rem;
                border-radius: .8rem;
                font-size: 1.25rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center py-2 py-lg-4">
        <div class="col-lg-7 col-xl-6">
            <div class="card voucher-redeem-card shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <header class="d-flex align-items-center gap-3 mb-4">
                        <span class="voucher-redeem-icon" aria-hidden="true">
                            <i class="bi bi-ticket-perforated"></i>
                        </span>
                        <div>
                            <h1 class="h3 mb-1">{{ trans('vouchers::messages.title') }}</h1>
                            <p class="text-body-secondary mb-0">{{ trans('vouchers::messages.description') }}</p>
                        </div>
                    </header>

                    @if(! $vouchersEnabled)
                        <div class="alert alert-warning d-flex align-items-center gap-2 mb-0" role="status">
                            <i class="bi bi-pause-circle-fill" aria-hidden="true"></i>
                            <span>{{ trans('vouchers::messages.errors.disabled') }}</span>
                        </div>
                    @else
                        <form action="{{ route('vouchers.redeem') }}" method="POST" id="captcha-form">
                            @csrf
                            <input type="hidden" name="request_token" value="{{ old('request_token', $requestToken) }}">

                            <div class="mb-4">
                                <label class="form-label fw-semibold" for="codeInput">{{ trans('vouchers::messages.fields.code') }}</label>
                                <div class="voucher-input-wrap">
                                    <i class="bi bi-key" aria-hidden="true"></i>
                                    <input type="text" class="form-control form-control-lg font-monospace text-uppercase voucher-code-input @error('code') is-invalid @enderror" id="codeInput" name="code" value="{{ old('code', $initialCode) }}" minlength="8" maxlength="14" pattern="[A-Za-z0-9-]{8,14}" placeholder="{{ trans('vouchers::messages.placeholders.code') }}" aria-describedby="codeHelp" autocomplete="one-time-code" autocapitalize="characters" spellcheck="false" required autofocus>
                                </div>
                                @error('code')
                                    <div class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></div>
                                @enderror
                                <div class="form-text" id="codeHelp">{{ trans('vouchers::messages.help.code') }}</div>
                            </div>

                            @guest
                                <div class="mb-4">
                                    <label class="form-label fw-semibold" for="usernameInput">{{ $userAttributeName }}</label>
                                    <div class="voucher-input-wrap">
                                        <i class="bi bi-person" aria-hidden="true"></i>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="usernameInput" name="username" value="{{ old('username') }}" maxlength="100" aria-describedby="usernameHelp" autocomplete="username">
                                    </div>
                                    @error('username')
                                        <div class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></div>
                                    @enderror
                                    <div class="form-text" id="usernameHelp">{{ trans('vouchers::messages.help.guest') }}</div>
                                </div>
                            @else
                                <div class="alert alert-info voucher-recipient mb-4" role="status">
                                    <i class="bi bi-person-check-fill fs-4" aria-hidden="true"></i>
                                    <span>{{ trans('vouchers::messages.logged_as', ['user' => auth()->user()->name]) }}</span>
                                </div>
                            @endguest

                            @include('elements.captcha', ['center' => true])

                            <button type="submit" class="btn btn-primary btn-lg voucher-submit w-100 fw-semibold">
                                <i class="bi bi-ticket-perforated me-1" aria-hidden="true"></i>
                                {{ trans('vouchers::messages.actions.redeem') }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
