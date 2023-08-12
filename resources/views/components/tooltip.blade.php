@props(['href', 'tooltip'])
<a href="{{ $href ?? '#' }}" {{ $attributes->merge(['class' => "group flex relative"]) }}>
    <span class="cursor-pointer">{{ $slot }}</span>
    <span class="group-hover:opacity-100 transition-opacity bg-slate-800 text-sm text-gray-100 rounded-md absolute -translate-x-1/2 -translate-y-full opacity-0 p-2 after:content-[''] after:absolute after:left-1/2 after:top-full after:-translate-x-1/2 after:border-8 after:border-x-transparent after:border-b-transparent after:border-t-slate-800 shadow-lg">
        {{ $tooltip }}
    </span>
</a>