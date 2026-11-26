@props(['title', 'text' => null, 'icon' => 'inbox'])
<div class="flex flex-col items-center px-6 py-14 text-center">
    <span class="grid size-14 place-items-center rounded-2xl bg-brand-100 text-brand-600"><x-icon :name="$icon" class="size-7" /></span>
    <h3 class="mt-4 text-base font-bold text-brand-950">{{ $title }}</h3>
    @if($text)<p class="mt-1 max-w-sm text-sm text-slate-500">{{ $text }}</p>@endif
    @if(!$slot->isEmpty())<div class="mt-5">{{ $slot }}</div>@endif
</div>
