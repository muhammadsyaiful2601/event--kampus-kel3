<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl">
        <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
            <div class="mb-2 mb-md-0">
                &#169;
                <script>
                    document.write(new Date().getFullYear());
                </script>
                made By kelompok 3
            </div>
            <div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                        id="footerLangDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class='bx bx-globe me-1'></i>
                        {{ app()->getLocale() == 'id' ? 'Bahasa Indonesia' : 'English' }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="footerLangDropdown">
                        <li><a class="dropdown-item {{ app()->getLocale() == 'id' ? 'active' : '' }}"
                                href="{{ route('lang.switch', 'id') }}">Bahasa Indonesia</a></li>
                        <li><a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                                href="{{ route('lang.switch', 'en') }}">English</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>