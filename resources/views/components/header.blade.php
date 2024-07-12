<div class="flex justify-between">
    <div>
        <h2 class="text-3xl tracking-wide">{{ $title }}</h2>
        <div class="flex gap-3 mt-4">
            <span class="text-sm text-gray-400">Master</span>
            <span class="text-sm text-gray-400">/</span>
            <span class="text-sm">{{ $title }} </span>
        </div>
    </div>
    {{ $slot }}
</div>
