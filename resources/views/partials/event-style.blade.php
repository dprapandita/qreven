{{-- Gaya halaman publik event. Opsional: kirim $accent (hex) untuk mengganti warna ungu bawaan. --}}
@php
    $accent = (isset($accent) && preg_match('/^#[0-9a-f]{3,8}$/i', $accent)) ? $accent : '#7c3aed';
@endphp
<style>
    :root {
        --ev-accent: {{ $accent }};
        --ev-soft: color-mix(in srgb, {{ $accent }} 10%, white);
        --ev-ink: #1e1b4b;
    }
    .ev-hero {
        background:
            radial-gradient(900px 420px at 85% -10%, color-mix(in srgb, var(--ev-accent) 60%, #f0abfc), transparent 62%),
            radial-gradient(600px 380px at 0% 110%, color-mix(in srgb, var(--ev-accent) 70%, #1e1b4b), transparent 70%),
            linear-gradient(135deg, #2e1065, color-mix(in srgb, var(--ev-accent) 80%, #2e1065));
    }
    .ev-chip { display:inline-flex; align-items:center; gap:.4rem; padding:.4rem .8rem; border-radius:999px;
               background:rgb(255 255 255 / .14); box-shadow: inset 0 0 0 1px rgb(255 255 255 / .22); font-size:.8rem; font-weight:600; }
    .ev-section-title { font-size:1.25rem; font-weight:800; color:var(--ev-ink); letter-spacing:-.01em; }
    .ev-timeline { position:relative; padding-left:1.75rem; }
    .ev-timeline::before { content:''; position:absolute; left:.45rem; top:.4rem; bottom:.4rem; width:2px;
                           background:linear-gradient(var(--ev-accent), transparent); border-radius:2px; }
    .ev-dot { position:absolute; left:-1.75rem; top:.35rem; width:.95rem; height:.95rem; border-radius:50%;
              background:#fff; border:3px solid var(--ev-accent); }
    .ev-ticket { border:1px solid color-mix(in srgb, var(--ev-accent) 22%, white); background:var(--ev-soft); border-radius:1rem; }
    .ev-ticket + .ev-ticket { margin-top:.75rem; }
</style>
