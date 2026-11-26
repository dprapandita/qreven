@props(['action', 'confirm' => 'Yakin ingin menghapus data ini?'])
<form method="POST" action="{{ $action }}" onsubmit="return confirm(@js($confirm))" class="inline">
    @csrf @method('DELETE')
    <button type="submit" {{ $attributes->merge(['class' => 'btn-ghost btn-sm text-rose-600 hover:bg-rose-50']) }}>{{ $slot }}</button>
</form>
