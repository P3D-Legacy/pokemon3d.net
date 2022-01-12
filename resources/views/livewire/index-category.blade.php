<div>
    @foreach ($categories as $category)
        <div class="flex items-center justify-between">
            <div class="text-red-400">
                {{ $category->name }}
            </div>
        </div>
    @endforeach
</div>