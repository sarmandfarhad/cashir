<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        :root {
            color-scheme: dark;
            --text: #ffffff;
            --muted: rgba(255, 255, 255, 0.82);
            --line: rgba(255, 255, 255, 0.88);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Arial, sans-serif;
            color: var(--text);
            background: #061a3e;
        }

        .shell {
            width: 100%;
            min-height: 100vh;
            position: relative;
            background:
                radial-gradient(circle at 48% 38%, rgba(40, 73, 181, 0.95) 0%, rgba(20, 51, 130, 0.82) 33%, transparent 62%),
                linear-gradient(135deg, #102d66 0%, #102b65 43%, #061a3e 100%);
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
            right: -43vw;
            bottom: -52vw;
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
            padding: 0 15px;
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
            margin-top: 38px;
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
            margin-top: 10px;
            color: var(--muted);
            text-align: right;
        }

        .error {
            margin: -15px 0 10px;
            color: #ffd6d6;
            text-align: left;
        }

        @media (max-width: 640px) {
            .content {
                padding: 32px 22px;
            }

            .panel {
                width: min(310px, 100%);
            }

            .wave {
                right: -760px;
                bottom: -570px;
            }
        }
    </style>
</head>
<body>
    <div class="shell">
        <div class="wave" aria-hidden="true"></div>
        <div class="content">
            <form class="panel" method="POST" action="{{ route('login') }}">
                @csrf
                <svg class="logo" viewBox="0 0 88 76" fill="none" aria-hidden="true">
                    <path d="M5 9H22L37 51H69L80 24H67" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M52 33V5M52 5L42 15M52 5L62 15" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="42" cy="65" r="4.5" stroke="currentColor" stroke-width="2.3"/>
                    <circle cx="67" cy="65" r="4.5" stroke="currentColor" stroke-width="2.3"/>
                </svg>
                <h1>Login</h1>

                <label class="field">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <circle cx="12" cy="8" r="3.5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M5.5 19C6.4 15.7 8.5 14 12 14s5.6 1.7 6.5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Username" aria-label="Email address" autocomplete="email" required autofocus>
                </label>

                <label class="field">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <rect x="4.5" y="10" width="15" height="10" rx="1.5" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M8 10V7.5a4 4 0 0 1 8 0V10" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    <input type="password" name="password" placeholder="Password" autocomplete="current-password" required>
                </label>

                @if ($errors->any())
                    <div class="error">{{ $errors->first() }}</div>
                @endif

                <div class="actions">
                    <button type="submit">Login</button>
                    <div class="hint">Forgot password?</div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>