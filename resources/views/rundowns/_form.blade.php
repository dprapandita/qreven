@php $dt = fn ($v) => $v ? \Illuminate\Support\Carbon::parse($v)->format('Y-m-d\TH:i') : ''; @endphp
<div class="grid gap-6">
    <x-field name="title" label="Judul sesi" required>
        <input id="title" name="title" class="input" value="{{ old('title', $rundown->title ?? '') }}" placeholder="Contoh: Pembukaan & keynote" required>
    </x-field>
    <x-field name="speaker" label="Pembicara / pengisi acara">
        <input id="speaker" name="speaker" class="input" value="{{ old('speaker', $rundown->speaker ?? '') }}">
    </x-field>
    <div class="grid gap-6 sm:grid-cols-2">
        <x-field name="starts_at" label="Mulai" required>
            <input id="starts_at" name="starts_at" type="datetime-local" class="input" value="{{ old('starts_at', $dt($rundown->starts_at ?? null)) }}" required>
        </x-field>
        <x-field name="ends_at" label="Selesai">
            <input id="ends_at" name="ends_at" type="datetime-local" class="input" value="{{ old('ends_at', $dt($rundown->ends_at ?? null)) }}">
        </x-field>
    </div>
    <x-field name="description" label="Keterangan">
        <textarea id="description" name="description" rows="3" class="input">{{ old('description', $rundown->description ?? '') }}</textarea>
    </x-field>
</div>
