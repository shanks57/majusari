@props(['url', 'bgColor' => 'bg-[#6634BB]', 'textColor' => 'text-[#F8F8F8]', 'icon' => 'pl-2 ph ph-plus', 'borderButton' => '', 'borderColor' => ''])

<a href="{{ $url }}" class="px-4 py-3 rounded-lg {{ $bgColor }} {{ $textColor }} flex items-center space-x-1 font-medium {{ $borderButton }} {{ $borderColor }}">
    <span>{{ $slot }}</span>
    @if($icon)
        <i class="{{ $icon }}"></i>
    @endif
</a>