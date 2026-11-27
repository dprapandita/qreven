@props(['title', 'subtitle' => null, 'back' => null])
<div class="mb-6 flex flex-col gap-4 border-b border-line pb-5 sm:flex-row sm:items-end sm:justify-between">
    <div class="min-w-0">
        @if($back)
            <a href="{{ $back }}" class="mb-2 inline-flex items-center gap-1.5 text-xs font-medium text-muted transition-colors duration-150 hover:text-ink">
                <x-icon name="back" class="size-3.5" /> Kembali
            </a>
        @endif
        <h1 class="page-title truncate">{{ $title }}</h1>
        @if($subtitle)<p class="mt-1 text-sm text-muted">{{ $subtitle }}</p>@endif
    </div>
    @if(isset($actions) && !$actions->isEmpty())
        <div class="flex shrink-0 flex-wrap items-center gap-2">{{ $actions }}</div>
    @endif
</div>
