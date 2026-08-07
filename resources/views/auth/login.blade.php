<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('layout.brand') }}</title>
    <style>
        :root {
            color-scheme: dark;
            --bg-1: #071834;
            --bg-2: #0c2b7b;
            --bg-3: #1b46c4;
            --card: rgba(6, 22, 56, 0.55);
            --line: rgba(255, 255, 255, 0.72);
            --text: rgba(255, 255, 255, 0.96);
            --muted: rgba(255, 255, 255, 0.72);
            --accent: #ffffff;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Arial, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at 50% 28%, rgba(59, 96, 226, 0.9) 0%, rgba(19, 50, 137, 0.92) 30%, transparent 58%),
                radial-gradient(circle at 72% 108%, rgba(255, 255, 255, 0.06), transparent 34%),
                linear-gradient(135deg, var(--bg-3) 0%, #133289 30%, var(--bg-2) 65%, var(--bg-1) 100%);
            display: grid;
            place-items: center;
            padding: 32px 16px;
        }

        .shell {
            width: min(720px, 100%);
            min-height: 560px;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35);
            overflow: hidden;
        }

        .shell::before,
        .shell::after {
            content: '';
            position: absolute;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .shell::before {
            width: 780px;
            height: 780px;
            right: -450px;
            top: -140px;
            background: radial-gradient(circle, rgba(255,255,255,0.08), transparent 64%);
        }

        .shell::after {
            width: 680px;
            height: 680px;
            left: -300px;
            bottom: -360px;
            background: radial-gradient(circle, rgba(255,255,255,0.05), transparent 62%);
        }

        .content {
            position: relative;
            z-index: 1;
            min-height: 560px;
            display: grid;
            place-items: center;
            padding: 56px 24px;
        }

        .panel {
            width: min(340px, 100%);
            text-align: center;
        }

        .logo {
            width: 88px;
            height: 88px;
            margin: 0 auto 26px;
            border-radius: 24px;
            border: 2px solid rgba(255, 255, 255, 0.9);
            position: relative;
        }

        .logo::before {
            content: '';
            position: absolute;
            inset: 20px 18px 22px;
            border: 2px solid rgba(255, 255, 255, 0.95);
            border-top: none;
            border-radius: 0 0 22px 22px;
            transform: skewX(-12deg);
        }

        .logo::after {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            border: 2px solid rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            left: 23px;
            bottom: 10px;
            box-shadow: 24px 0 0 -2px transparent, 24px 0 0 0 rgba(255,255,255,0.95);
        }

        h1 {
            margin: 0 0 24px;
            font-size: 30px;
            letter-spacing: 0.18em;
            font-weight: 700;
        }

        .field {
            display: flex;
            align-items: center;
            gap: 12px;
            height: 38px;
            margin: 14px 0;
            padding: 0 12px;
            border: 1px solid var(--line);
            background: rgba(10, 27, 66, 0.24);
            color: var(--text);
        }

        .field span {
            width: 18px;
            text-align: center;
            opacity: 0.9;
            font-size: 15px;
        }

        .field input {
            flex: 1;
            border: 0;
            outline: 0;
            background: transparent;
            color: var(--text);
            font-size: 11px;
            letter-spacing: 0.12em;
        }

        .field input::placeholder {
            color: rgba(255, 255, 255, 0.72);
        }

        .actions {
            margin-top: 28px;
        }

        button {
            width: 100%;
            height: 30px;
            border: 0;
            background: #fff;
            color: #1e43a8;
            font-weight: 700;
            letter-spacing: 0.14em;
            cursor: pointer;
        }

        .hint,
        .error {
            margin-top: 10px;
            font-size: 13px;
        }

        .hint {
            color: var(--muted);
            text-align: right;
        }

        .error {
            color: #ffd1d1;
            text-align: left;
        }

        /* Language Switcher */
        .language-switcher {
            position: fixed;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 8px;
            z-index: 1000;
        }

        .language-switcher button {
            width: auto;
            min-width: 60px;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            color: var(--text);
            font-weight: 600;
            font-size: 12px;
            cursor: pointer;
            backdrop-filter: blur(4px);
        }

        .language-switcher button.active {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
        }

        /* Dark Mode Toggle */
        .theme-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            color: var(--text);
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
            z-index: 1000;
        }

        .theme-toggle.active {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
        }

        @media (max-width: 640px) {
            .shell {
                min-height: 520px;
            }

            .content {
                padding: 40px 18px;
            }

            h1 {
                font-size: 24px;
            }

            .language-switcher {
                top: 10px;
                right: 10px;
                gap: 4px;
            }

            .language-switcher button {
                min-width: 50px;
                padding: 6px 8px;
                font-size: 10px;
            }

            .theme-toggle {
                top: 10px;
                left: 10px;
                width: 35px;
                height: 35px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Dark Mode Toggle -->
    <button class="theme-toggle" id="theme-toggle" aria-label="{{ __('navbar.theme') }}">
        🌙
    </button>

    <!-- Language Switcher -->
    <div class="language-switcher" id="language-switcher">
        <button class="lang-en" data-lang="en">{{ __('navbar.english') }}</button>
        <button class="lang-ar" data-lang="ar">{{ __('navbar.arabic') }}</button>
    </div>

    <div class="shell">
        <div class="content">
            <form class="panel" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="logo" aria-hidden="true"></div>
                <h1>{{ __('auth.login') }}</h1>

                <label class="field">
                    <span>u</span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="{{ __('auth.username') }}" autocomplete="email" required>
                </label>

                <label class="field">
                    <span>p</span>
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
            'use strict';

            // Theme toggle functionality
            const themeToggle = document.getElementById('theme-toggle');
            const htmlElement = document.documentElement;

            // Check for saved theme preference or use system preference
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                if (savedTheme === 'dark') {
                    htmlElement.classList.add('dark');
                    themeToggle.textContent = '☀️';
                } else {
                    htmlElement.classList.remove('dark');
                    themeToggle.textContent = '🌙';
                }
            } else {
                // Check system preference
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    htmlElement.classList.add('dark');
                    themeToggle.textContent = '☀️';
                }
            }

            themeToggle.addEventListener('click', () => {
                const isDark = htmlElement.classList.toggle('dark');
                if (isDark) {
                    themeToggle.textContent = '☀️';
                    localStorage.setItem('theme', 'dark');
                } else {
                    themeToggle.textContent = '🌙';
                    localStorage.setItem('theme', 'light');
                }
            });

            // Language switcher functionality
            const languageButtons = document.querySelectorAll('.language-switcher button');
            const currentLanguage = localStorage.getItem('language') || 'en';

            // Set initial active state
            languageButtons.forEach(btn => {
                if (btn.dataset.lang === currentLanguage) {
                    btn.classList.add('active');
                }

                btn.addEventListener('click', () => {
                    const lang = btn.dataset.lang;

                    // Update active state
                    languageButtons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    // Save preference
                    localStorage.setItem('language', lang);

                    // Send request to change language
                    fetch('/locale', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: new URLSearchParams({ 'locale': lang })
                    })
                    .then(response => {
                        if (response.ok) {
                            // Reload page to apply new language
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error changing language:', error);
                    });
                });
            });
        })();
    </script>
</body>
</html>