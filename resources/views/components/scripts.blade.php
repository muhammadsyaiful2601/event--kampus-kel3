<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

<script src="{{ asset('assets/js/main.js') }}"></script>

<script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

<script>
    // Save current locale to localStorage for client-side persistence
    document.addEventListener('DOMContentLoaded', function() {
        const currentLocale = '{{ app()->getLocale() }}';
        localStorage.setItem('locale', currentLocale);

        // Listen for language switch link clicks to update localStorage before navigation
        document.querySelectorAll('[href*="lang/"], .dropdown-item[href*="lang.switch"]').forEach(function(el) {
            el.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href && href.includes('lang/')) {
                    const parts = href.split('/');
                    const locale = parts[parts.length - 1];
                    if (locale === 'en' || locale === 'id') {
                        localStorage.setItem('locale', locale);
                    }
                }
            });
        });
    });
</script>