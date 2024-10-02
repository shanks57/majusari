@props(['bgColor' => 'bg-[#6634BB]', 'textColor' => 'text-[#F8F8F8]', 'icon' => 'pl-2 ph ph-plus', 'borderButton' => '', 'borderColor' => '', 'dataHsOverlay' => '#hs-add-modal'])
@php
$disabled = $attributes->get('disabled');
@endphp
<button type="button" class="px-4 py-3 disabled:bg-neutral-300 disabled:cursor-not-allowed text-sm rounded-lg {{ $bgColor }} {{ $textColor }} flex items-center space-x-1 font-medium {{ $borderButton }} {{ $borderColor }}" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-scale-animation-modal" data-hs-overlay="{{ $dataHsOverlay }}" {{ $disabled ? 'disabled' : '' }}>
    <span>{{ $slot }}</span>
    @if($icon)
    <i class="{{ $icon }}"></i>
    @endif
</button>