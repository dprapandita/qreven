@props(['label', 'value', 'icon' => 'grid', 'hint' => null])
<div class="card card-pad flex items-start justify-between gap-4">
    <div class="min-w-0">
        <p class="eyebrow">{{ $label }}</p>
        <p class="font-display mt-2 text-3xl font-bold tracking-tight text-ink">{{ $value }}</p>
        @if($hint)<p class="mt-1 text-xs text-muted">{{ $hint }}</p>@endif
    </div>
    <span class="grid size-9 shrink-0 place-items-center rounded border border-teal/25 bg-teal/10 text-teal">
        <x-icon :name="$icon" class="size-4" />
    </span>
</div>
