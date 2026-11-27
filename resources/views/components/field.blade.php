@props(['name', 'label', 'help' => null, 'required' => false])
<div {{ $attributes->merge(['class' => 'space-y-1.5']) }}>
    <label for="{{ $name }}" class="label">
        {{ $label }} @if($required)<span class="text-error">*</span>@endif
    </label>
    {{ $slot }}
    @if($help)<p class="help">{{ $help }}</p>@endif
    @error($name)<p class="error-text">{{ $message }}</p>@enderror
</div>
