@props(['slug' => '', 'class' => 'h-6 w-6'])

@php
    $common = 'fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"';
@endphp

@switch($slug)
    @case('womens-fashion')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" {!! $common !!}>
            <path d="M9 4l3 2 3-2 3 3-2.5 2L18 20H6l2.5-11L6 7l3-3z" />
            <path d="M10.5 4.2a1.5 1.8 0 003 0" />
        </svg>
        @break

    @case('mens-fashion')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" {!! $common !!}>
            <path d="M8 4L4 6.5 6 10l2-1.2V20h8V8.8L18 10l2-3.5L16 4l-2 2h-4L8 4z" />
        </svg>
        @break

    @case('shoes')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" {!! $common !!}>
            <path d="M3 17.5c0-1.4.7-2.3 1.8-3l4-2.6c.7-.5 1.5-.7 2.3-.5l1.4.3V9c0-.6.4-1 1-1s1 .3 1.4.8l1.9 2.5c.5.6 1.2 1 2 1.2l1.6.4c1 .3 1.6 1.1 1.6 2.1v1c0 .8-.7 1.5-1.5 1.5H4c-.6 0-1-.4-1-1v-1z" />
        </svg>
        @break

    @case('beauty-cosmetics')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" {!! $common !!}>
            <path d="M9 3h6l1 3H8l1-3z" />
            <path d="M7 6h10l1 4v9a2 2 0 01-2 2H8a2 2 0 01-2-2v-9l1-4z" />
            <path d="M9 12h6" />
        </svg>
        @break

    @case('accessories')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" {!! $common !!}>
            <path d="M7 10V7a5 5 0 0110 0v3" />
            <rect x="4" y="10" width="16" height="10" rx="2" />
            <circle cx="12" cy="15" r="1.4" />
        </svg>
        @break

    @default
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" {!! $common !!}>
            <path d="M20.6 12.6L12 21.2 2.8 12 11.4 3.4H18a2 2 0 012 2v7.2z" />
            <circle cx="15" cy="7.5" r="1.4" />
        </svg>
@endswitch
