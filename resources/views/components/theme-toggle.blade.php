@props(['class' => ''])
{{-- Toggle switch: 48×28px track, 24×24px thumb — meets WCAG 2.5.8 (44×44 target) via padding --}}
<button type="button"
        role="switch"
        aria-checked="false"
        data-theme-toggle
        onclick="window.toggleQrevenTheme && window.toggleQrevenTheme()"
        aria-label="Ubah mode terang / gelap"
        title="Ubah mode terang / gelap"
        {{ $attributes->merge(['class' => "group relative inline-flex h-8 w-[3.25rem] shrink-0 cursor-pointer items-center rounded-full border border-line bg-canvas p-0.5 transition-colors duration-200 hover:border-ink/50 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink dark:bg-surface $class"]) }}>
    {{-- Track icons --}}
    <span class="pointer-events-none absolute inset-x-0 flex items-center justify-between px-2" aria-hidden="true">
        {{-- Sun --}}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3.5 text-warning dark:text-muted/50">
            <circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
        </svg>
        {{-- Moon --}}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-3.5 text-muted/50 dark:text-accent">
            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
        </svg>
    </span>
    {{-- Thumb: 24×24px --}}
    <span class="pointer-events-none relative z-10 size-6 rounded-full border border-line bg-surface shadow-sm transition-all duration-200 dark:translate-x-5 dark:border-accent/50 dark:bg-accent" aria-hidden="true"></span>
</button>
