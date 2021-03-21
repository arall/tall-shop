<select {{ $attributes->merge(['class' => 'max-w-lg block focus:ring-indigo-500 focus:border-indigo-500 w-full shadow-sm sm:max-w-xs sm:text-sm border-gray-300 rounded-md']) }}>
    {{ $slot }}
</select>
