@once
    @push('footer-scripts')
        <script>
            document.addEventListener('input', function (event) {
                if (!event.target.matches('[data-integer-input]')) {
                    return;
                }

                event.target.value = event.target.value.replace(/[^0-9]/g, '');
            });
        </script>
    @endpush
@endonce
