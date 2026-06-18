<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="layout-menu-fixed layout-compact"
    data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template-free">

@include('components.header')

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            @include('components.sidebar')

            <div class="layout-page">

                <div class="content-wrapper">

                    <div class="container-xxl flex-grow-1 container-p-y">

                        {{-- Isi Konten Halaman Mulai di Sini --}}
                        <div class="card">
                            <div class="card-body">
                                <h5>Halaman Depan</h5>
                                <p>Tempat konten dashboard utama kamu.</p>
                            </div>
                        </div>
                        {{-- Batas Akhir Konten Halaman --}}

                    </div>
                    @include('components.footer')

                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>

        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    @include('components.scripts')
</body>

</html>
