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
    <title>{{ __('auth.login') }}</title>
    <script>
        (() => {
            const savedTheme = localStorage.getItem('cashir-theme');
            if (savedTheme === 'light' || savedTheme === 'dark') {
                document.documentElement.dataset.theme = savedTheme;
            }
        })();
    </script>
    <style>
        :root {
            color-scheme: light;
            --text: #ffffff;
            --muted: rgba(255, 255, 255, 0.82);
            --line: rgba(255, 255, 255, 0.88);
            --login-bg: #0e3479;
            --login-glow: rgba(55, 101, 225, 0.92);
            --login-deep: #0b2658;
        }

        html[data-theme="dark"] {
            color-scheme: dark;
            --login-bg: #04132f;
            --login-glow: rgba(30, 62, 151, 0.88);
            --login-deep: #020b1d;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Arial, sans-serif;
            color: var(--text);
            background: var(--login-deep);
        }

        .shell {
            width: 100%;
            min-height: 100vh;
            position: relative;
            background:
                radial-gradient(circle at 48% 38%, var(--login-glow) 0%, rgba(20, 51, 130, 0.82) 33%, transparent 62%),
                linear-gradient(135deg, var(--login-bg) 0%, #102b65 43%, var(--login-deep) 100%);
            overflow: hidden;
            isolation: isolate;
        }

        .wave {
            position: absolute;
            z-index: -1;
            width: 112vw;
            height: 80vw;
            min-width: 1050px;
            min-height: 750px;
            inset-inline-end: -43vw;
            inset-block-end: -52vw;
            border-radius: 50%;
            border: clamp(30px, 5vw, 80px) solid rgba(43, 75, 151, 0.22);
            box-shadow:
                0 0 0 clamp(45px, 8vw, 125px) rgba(33, 67, 144, 0.18),
                0 0 0 clamp(100px, 15vw, 235px) rgba(28, 58, 126, 0.16),
                0 0 0 clamp(165px, 23vw, 360px) rgba(21, 48, 104, 0.16);
            transform: rotate(-9deg);
        }

        .shell::before {
            content: '';
            position: absolute;
            inset: 0;
            z-index: -2;
            background: linear-gradient(110deg, rgba(255, 255, 255, 0.025), transparent 42%);
        }

        .content {
            position: relative;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 40px 24px;
        }

        .panel {
            width: min(340px, 100%);
            text-align: center;
            transform: translateY(-1vh);
        }

        .logo {
            width: 82px;
            height: 72px;
            display: block;
            margin: 0 auto 4px;
        }

        h1 {
            margin: 0 0 46px;
            font-size: 12px;
            line-height: 1;
            letter-spacing: 0.42em;
            text-transform: uppercase;
            font-weight: 400;
        }

        .field {
            display: flex;
            align-items: center;
            gap: 18px;
            height: 42px;
            margin: 0 0 28px;
            padding-inline: 15px;
            border: 1px solid var(--line);
            border-radius: 2px;
            background: rgba(16, 43, 111, 0.16);
            color: var(--text);
        }

        .field svg {
            width: 17px;
            height: 17px;
            flex: 0 0 17px;
            color: rgba(255, 255, 255, 0.75);
        }

        .field input {
            flex: 1;
            min-width: 0;
            border: 0;
            outline: 0;
            background: transparent;
            color: var(--text);
            font: inherit;
            font-size: 10px;
            letter-spacing: 0.08em;
        }

        .field input::placeholder {
            color: rgba(255, 255, 255, 0.68);
            text-transform: uppercase;
        }

        .actions {
            margin-block-start: 38px;
        }

        button {
            width: 100%;
            height: 42px;
            border: 0;
            border-radius: 2px;
            background: #fff;
            color: #123c93;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 160ms ease, transform 160ms ease;
        }

        button:hover {
            background: #f0f4ff;
        }

        button:active {
            transform: translateY(1px);
        }

        .hint,
        .error {
            font-size: 11px;
        }

        .hint {
            margin-block-start: 10px;
            color: var(--muted);
            text-align: end;
        }

        .error {
            margin: -15px 0 10px;
            color: #ffd6d6;
            text-align: start;
        }

        .preferences {
            position: absolute;
            inset-block-start: 20px;
            inset-inline-end: 20px;
            z-index: 2;
            display: flex;
            gap: 8px;
        }

        .preference-group {
            display: flex;
            gap: 3px;
            padding: 3px;
            border: 1px solid rgba(255,255,255,.35);
            border-radius: 8px;
            background: rgba(3, 18, 52, .28);
            backdrop-filter: blur(8px);
        }

        .preference-group form { display: flex; }
        .preference-button {
            width: auto;
            height: 30px;
            padding-inline: 10px;
            border-radius: 6px;
            color: var(--muted);
            background: transparent;
            font-size: 10px;
            text-transform: none;
        }
        .preference-button.is-active {
            color: #123c93;
            background: #fff;
        }

        @media (max-width: 640px) {
            .content {
                padding: 32px 22px;
            }

            .panel {
                width: min(310px, 100%);
            }

            .wave {
                inset-inline-end: -760px;
                inset-block-end: -570px;
            }

            .preferences {
                inset-block-start: 12px;
                inset-inline-end: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="shell">
        <div class="wave" aria-hidden="true"></div>
        <div class="preferences">
            <div class="preference-group" role="group" aria-label="{{ __('preferences.language') }}">
                @foreach (['en' => 'EN', 'ar' => 'ع'] as $locale => $label)
                    <form method="POST" action="{{ route('preferences.locale') }}">
                        @csrf
                        <input type="hidden" name="locale" value="{{ $locale }}">
                        <button class="preference-button {{ app()->getLocale() === $locale ? 'is-active' : '' }}" type="submit">{{ $label }}</button>
                    </form>
                @endforeach
            </div>
            <div class="preference-group" role="group" aria-label="{{ __('preferences.theme') }}">
                <button class="preference-button" type="button" data-theme-option="light" title="{{ __('preferences.light') }}">☀</button>
                <button class="preference-button" type="button" data-theme-option="dark" title="{{ __('preferences.dark') }}">☾</button>
            </div>
        </div>
        <div class="content">
            <form class="panel" method="POST" action="{{ route('login') }}">
                @csrf
                <svg class="logo" viewBox="0 0 88 76" fill="none" aria-hidden="true">
                    <path d="M5 9H22L37 51H69L80 24H67" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M52 33V5M52 5L42 15M52 5L62 15" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="42" cy="65" r="4.5" stroke="currentColor" stroke-width="2.3"/>
                    <circle cx="67" cy="65" r="4.5" stroke="currentColor" stroke-width="2.3"/>
                </svg>
                <h1>{{ __('auth.login') }}</h1>

                <label class="field">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="8" r="3.5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M5.5 19C6.4 15.7 8.5 14 12 14s5.6 1.7 6.5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('auth.username') }}" aria-label="{{ __('auth.emailAddress') }}" autocomplete="email" required autofocus>
                </label>

                <label class="field">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="4.5" y="10" width="15" height="10" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M8 10V7.5a4 4 0 0 1 8 0V10" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    <input type="password" name="password" placeholder="{{ __('auth.password') }}" autocomplete="current-password" required>
                </label>

                @if ($errors->any())
                    <div class="error">{{ $errors->first() }}</div>
                @endif

                <div class="actions">
                    <button type="submit">{{ __('auth.login') }}</button>
                    <div class="hint">{{ __('auth.forgotPassword') }}</div>
                </div>
            </form>
        </div>
    </div>
    <script>
        (() => {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const buttons = [...document.querySelectorAll('[data-theme-option]')];
            const setActiveTheme = (theme) => {
                document.documentElement.dataset.theme = theme;
                buttons.forEach((button) => button.classList.toggle('is-active', button.dataset.themeOption === theme));
            };

            setActiveTheme(document.documentElement.dataset.theme);
            buttons.forEach((button) => button.addEventListener('click', async () => {
                const theme = button.dataset.themeOption;
                setActiveTheme(theme);
                localStorage.setItem('cashir-theme', theme);
                await fetch('{{ route('preferences.theme') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ theme }),
                });
            }));
        })();
    </script>
</body>
</html>