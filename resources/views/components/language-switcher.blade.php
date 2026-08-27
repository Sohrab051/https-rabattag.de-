@php
$currentLocale = app()->getLocale();
$otherLocale = $currentLocale === 'en' ? 'de' : 'en';
$params = request()->route() ? array_merge(request()->route()->parameters(), ['locale' => $otherLocale]) : ['locale' => $otherLocale];
$url = request()->route() ? route(request()->route()->getName(), $params) : url('/'.$otherLocale);
@endphp

<a href="{{ $url }}"
   x-on:click="try { document.cookie = 'locale={{ $otherLocale }};path=/;max-age=31536000' } catch (e) {}"
   class="inline-flex items-center rounded-lg border border-gray-300 px-2.5 py-1.5 text-xs font-semibold uppercase text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
    {{ $otherLocale }}
</a>
