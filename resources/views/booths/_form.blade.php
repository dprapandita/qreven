<div class="grid gap-6">
    <x-field name="name" label="Nama booth" required>
        <input id="name" name="name" class="input" value="{{ old('name', $booth->name ?? '') }}" placeholder="Contoh: Booth A1" required>
    </x-field>
    <x-field name="location" label="Lokasi / zona" help="Posisi booth di area event.">
        <input id="location" name="location" class="input" value="{{ old('location', $booth->location ?? '') }}" placeholder="Hall utama, dekat pintu masuk">
    </x-field>
    <x-field name="price" label="Harga sewa (Rp)" required>
        <input id="price" name="price" type="number" min="0" step="1000" class="input" value="{{ old('price', $booth->price ?? 0) }}" required>
    </x-field>
    <x-field name="description" label="Keterangan">
        <textarea id="description" name="description" rows="3" class="input" placeholder="Ukuran, fasilitas listrik, dan sebagainya.">{{ old('description', $booth->description ?? '') }}</textarea>
    </x-field>
</div>
