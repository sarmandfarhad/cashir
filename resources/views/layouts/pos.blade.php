<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    data-theme="{{ session('theme', 'light') }}"
>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('POS System'))</title>
    <script>
        (() => {
            const savedTheme = localStorage.getItem('cashir-theme');
            if (savedTheme === 'light' || savedTheme === 'dark') {
                document.documentElement.dataset.theme = savedTheme;
            }
        })();
    </script>
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>{!! file_get_contents(resource_path('css/pos.css')) !!}</style>
    @endif
    @stack('styles')
</head>
<body class="@yield('body-class')">
    <div class="pos-shell @hasSection('summary') pos-shell--with-summary @endif">
        @include('partials.pos-sidebar', ['active' => $activeNav ?? 'cashier'])

        <main class="pos-main @yield('main-class')" id="main-content">
            @yield('content')
        </main>

        @yield('summary')
    </div>

    @yield('overlays')
    @stack('scripts')

    <script>
        (() => {
            const token = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
            const activeTheme = document.documentElement.dataset.theme;

            document.querySelectorAll('[data-theme-option]').forEach((item) => {
                item.classList.toggle('is-active', item.dataset.themeOption === activeTheme);
            });

            document.querySelectorAll('[data-theme-option]').forEach((button) => {
                button.addEventListener('click', async () => {
                    const theme = button.dataset.themeOption;
                    document.documentElement.dataset.theme = theme;
                    localStorage.setItem('cashir-theme', theme);
                    document.querySelectorAll('[data-theme-option]').forEach((item) => {
                        item.classList.toggle('is-active', item.dataset.themeOption === theme);
                    });

                    await fetch('{{ route('preferences.theme') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ theme }),
                    });
                });
            });
        })();
    </script>
</body>
</html>
