@props(['disabled' => false, 'peer' => false])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => // $peer  ? 'w-full dark:bg-gray-900 dark:border-gray-700 px-0 border-0 border-b border-gray-300 text-gray-900 placeholder-transparent focus:outline-none focus:border-blue-600 focus:ring-0' :
    'w-full dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}>
    {{ $slot }}
</select>
