@props(['selected' => false])
<option {{ $attributes }} @selected($selected)>{{ $slot }}</option>