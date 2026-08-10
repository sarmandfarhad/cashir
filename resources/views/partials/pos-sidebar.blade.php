<aside class="pos-sidebar">
    <a class="pos-brand" href="{{ route('dashboard') }}">
        <span class="pos-brand__mark" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M3 4h3l2.2 10h9.6l2-7H8" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="10" cy="19" r="1.3" fill="currentColor"/>
                <circle cx="18" cy="19" r="1.3" fill="currentColor"/>
            </svg>
        </span>
        <span>
            <strong>{{ __('brand.name') }}</strong>
            <small>{{ __('brand.subtitle') }}</small>
        </span>
    </a>

    <nav class="pos-nav" aria-label="{{ __('nav.label') }}">
        <a class="{{ $active === 'cashier' ? 'is-active' : '' }}" href="{{ route('dashboard') }}">
            <span aria-hidden="true">▣</span>{{ __('nav.cashier') }}
        </a>
        <a class="{{ $active === 'inventory' ? 'is-active' : '' }}" href="{{ route('inventory.index') }}">
            <span aria-hidden="true">▥</span>{{ __('nav.inventory') }}
        </a>
        <a class="{{ $active === 'sales' ? 'is-active' : '' }}" href="{{ route('sales.index') }}">
            <span aria-hidden="true">◷</span>{{ __('nav.sales') }}
        </a>
    </nav>

    <div class="pos-preferences">
        <span class="pos-preferences__label">{{ __('preferences.theme') }}</span>
        <div class="segmented-control" role="group" aria-label="{{ __('preferences.theme') }}">
            @foreach (['light' => '☀', 'dark' => '☾'] as $theme => $icon)
                <button
                    class="{{ session('theme', 'light') === $theme ? 'is-active' : '' }}"
                    type="button"
                    data-theme-option="{{ $theme }}"
                    title="{{ __('preferences.'.$theme) }}"
                >{{ $icon }} <span>{{ __('preferences.'.$theme) }}</span></button>
            @endforeach
        </div>

        <span class="pos-preferences__label">{{ __('preferences.language') }}</span>
        <div class="segmented-control" role="group" aria-label="{{ __('preferences.language') }}">
            @foreach (['en' => 'EN', 'ar' => 'ع'] as $locale => $label)
                <form method="POST" action="{{ route('preferences.locale') }}">
                    @csrf
                    <input type="hidden" name="locale" value="{{ $locale }}">
                    <button class="{{ app()->getLocale() === $locale ? 'is-active' : '' }}" type="submit">{{ $label }}</button>
                </form>
            @endforeach
        </div>
    </div>

    <div class="pos-sidebar__footer">
        <div class="pos-operator">
            <span class="pos-operator__avatar">{{ strtoupper(substr(auth()->user()->name ?? 'C', 0, 1)) }}</span>
            <span>
                <small>{{ __('common.operator') }}</small>
                <strong>{{ auth()->user()->name ?? __('common.cashier') }}</strong>
            </span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="pos-logout" type="submit"><span aria-hidden="true">⇦</span>{{ __('nav.logout') }}</button>
        </form>
    </div>
</aside>
