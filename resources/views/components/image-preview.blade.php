@if ($getRecord())
    <div class="my-4">
        <label class="block text-sm font-medium text-gray-700">Current Image
        </label>
        <img src="data:image/png;base64,{{ $getRecord()->image }}" alt="Image preview" class="w-48 h-auto mt-2 rounded shadow">
    </div>
@endif
