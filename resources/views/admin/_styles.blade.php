@once
    @push('styles')
        <style>
            .vouchers-admin-card {
                overflow: hidden;
                border: 1px solid rgba(var(--bs-primary-rgb), .1);
                border-radius: .85rem;
                box-shadow: 0 .25rem 1rem rgba(0, 0, 0, .04);
            }

            .vouchers-admin-header {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 1rem;
                padding: 1.25rem 1.5rem;
                border-bottom: 1px solid var(--bs-border-color);
                background: rgba(var(--bs-primary-rgb), .035);
            }

            .vouchers-admin-heading {
                display: flex;
                align-items: center;
                gap: .85rem;
            }

            .vouchers-admin-icon {
                display: inline-flex;
                width: 2.65rem;
                height: 2.65rem;
                align-items: center;
                justify-content: center;
                flex: 0 0 auto;
                border-radius: .7rem;
                color: var(--bs-primary);
                background: rgba(var(--bs-primary-rgb), .12);
                font-size: 1.2rem;
            }

            .vouchers-admin-section {
                padding: 1.25rem;
                border: 1px solid var(--bs-border-color);
                border-radius: .75rem;
                background: var(--bs-body-bg);
            }

            .vouchers-admin-table thead th {
                padding-top: .85rem;
                padding-bottom: .85rem;
                border-bottom-width: 1px;
                color: var(--bs-secondary-color);
                font-size: .76rem;
                font-weight: 700;
                letter-spacing: .04em;
                text-transform: uppercase;
                white-space: nowrap;
            }

            .vouchers-admin-table tbody td {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }

            .vouchers-code {
                display: inline-block;
                padding: .3rem .55rem;
                border: 1px solid rgba(var(--bs-primary-rgb), .18);
                border-radius: .45rem;
                color: var(--bs-primary);
                background: rgba(var(--bs-primary-rgb), .07);
                font-size: .84rem;
                letter-spacing: .03em;
                white-space: nowrap;
            }

            .vouchers-action-group {
                display: inline-flex;
                gap: .35rem;
            }

            .vouchers-action-group .btn {
                display: inline-flex;
                width: 2.15rem;
                height: 2.15rem;
                align-items: center;
                justify-content: center;
                padding: 0;
                border-radius: .55rem;
            }

            .vouchers-empty-state {
                padding: 2.5rem 1rem;
                text-align: center;
            }

            .vouchers-empty-state .bi {
                display: block;
                margin-bottom: .75rem;
                color: var(--bs-secondary-color);
                font-size: 2rem;
            }

            .vouchers-reward-card {
                border-color: var(--bs-border-color);
                border-radius: .75rem;
                background: rgba(var(--bs-secondary-rgb), .025);
            }

            @media (max-width: 767.98px) {
                .vouchers-admin-header {
                    align-items: stretch;
                    flex-direction: column;
                    padding: 1rem;
                }

                .vouchers-admin-header > .btn {
                    width: 100%;
                }

                .vouchers-admin-card > .card-body {
                    padding: 1rem;
                }
            }
        </style>
    @endpush
@endonce
