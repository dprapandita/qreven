{{-- Editorial styles for public event subdomain pages --}}
<style>
    .ev-timeline {
        position: relative;
        padding-left: 1.5rem;
    }
    .ev-timeline::before {
        content: '';
        position: absolute;
        left: 0.3125rem;
        top: 0.35rem;
        bottom: 0.35rem;
        width: 1px;
        background: var(--color-line);
    }
    .ev-dot {
        position: absolute;
        left: -1.5rem;
        top: 0.35rem;
        width: 0.6875rem;
        height: 0.6875rem;
        border-radius: 2px;
        background: var(--color-accent);
        border: 1px solid var(--color-ink);
    }
</style>
