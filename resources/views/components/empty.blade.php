@props(['title', 'text' => null, 'message' => null, 'icon' => 'inbox'])
@php($copy = $text ?? $message)
<div class="flex flex-col items-center px-6 py-12 text-center">
    <span class="grid size-11 place-items-center rounded-md border border-line bg-canvas text-ink">
        <x-icon :name="$icon" class="size-5" />
    </span>
    <h3 class="font-display mt-4 text-base font-bold text-ink">{{ $title }}</h3>
    @if($copy)<p class="mt-1 max-w-sm text-sm text-muted">{{ $copy }}</p>@endif
    @if(!$slot->isEmpty())<div class="mt-5">{{ $slot }}</div>@endif
</div>
