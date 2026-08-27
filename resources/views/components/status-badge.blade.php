@props(['status'])

@php
$colors = [
    'draft' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
    'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
    'published' => 'bg-discount-100 text-discount-800 dark:bg-discount-800/40 dark:text-discount-200',
    'approved' => 'bg-discount-100 text-discount-800 dark:bg-discount-800/40 dark:text-discount-200',
    'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
    'expired' => 'bg-gray-200 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
    'paid' => 'bg-primary-100 text-primary-800 dark:bg-primary-900/40 dark:text-primary-300',
];
$color = $colors[$status] ?? 'bg-gray-100 text-gray-700';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold $color"]) }}>
    {{ __(ucfirst($status)) }}
</span>
