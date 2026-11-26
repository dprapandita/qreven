<div class="grid gap-6">
    <x-field name="name" label="Nama tiket" required>
        <input id="name" name="name" class="input" value="{{ old('name', $ticketType->name ?? '') }}" placeholder="Contoh: Early Bird" required>
    </x-field>
    <x-field name="description" label="Deskripsi">
        <textarea id="description" name="description" rows="3" class="input" placeholder="Apa saja yang didapat pembeli tiket ini?">{{ old('description', $ticketType->description ?? '') }}</textarea>
    </x-field>
    <div class="grid gap-6 sm:grid-cols-2">
        <x-field name="price" label="Harga (Rp)" help="Isi 0 untuk tiket gratis." required>
            <input id="price" name="price" type="number" min="0" step="1000" class="input" value="{{ old('price', $ticketType->price ?? 0) }}" required>
        </x-field>
        <x-field name="quota" label="Kuota" required>
            <input id="quota" name="quota" type="number" min="1" class="input" value="{{ old('quota', $ticketType->quota ?? '') }}" placeholder="100" required>
        </x-field>
    </div>
</div>
