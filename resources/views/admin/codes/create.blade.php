@extends('admin.layouts.admin')

@section('title', trans('vouchers::admin.codes.create'))

@include('vouchers::admin._styles')

@section('content')
    <form action="{{ route('vouchers.admin.codes.store') }}" method="POST">
        <div class="card vouchers-admin-card mb-4">
            <div class="vouchers-admin-header">
                <div class="vouchers-admin-heading">
                    <span class="vouchers-admin-icon"><i class="bi bi-ticket-perforated" aria-hidden="true"></i></span>
                    <div>
                        <h2 class="h5 mb-1">{{ trans('vouchers::admin.codes.create') }}</h2>
                        <p class="text-body-secondary mb-0">{{ trans('vouchers::admin.codes.form_description') }}</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                @include('vouchers::admin.codes._form')

                <button type="submit" class="btn btn-primary mt-2">
                    <i class="bi bi-check-lg" aria-hidden="true"></i> {{ trans('messages.actions.save') }}
                </button>
            </div>
        </div>
    </form>
@endsection
