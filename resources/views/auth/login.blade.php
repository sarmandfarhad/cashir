<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        }
    </style>
</head>
<body>
    <div class="shell">
        <div class="content">
            <form class="panel" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="logo" aria-hidden="true"></div>
                <h1>Login</h1>

                <label class="field">
                    <span>u</span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Username" autocomplete="email" required>
                </label>

                <label class="field">
                    <span>p</span>
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