@props(['label', 'value', 'icon' => 'grid', 'hint' => null])
<div class="card card-pad flex items-start gap-4">
    <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-brand-100 text-brand-700">
        <x-icon :name="$icon" class="size-5" />
    </span>
    <div class="min-w-0">
        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $label }}</p>
        <p class="mt-0.5 text-2xl font-extrabold text-brand-950">{{ $value }}</p>
        @if($hint)<p class="mt-0.5 text-xs text-slate-500">{{ $hint }}</p>@endif
    </div>
</div>
