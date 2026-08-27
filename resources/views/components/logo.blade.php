@props(['class' => 'h-8 w-auto'])

<svg viewBox="0 0 640 160" xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => $class]) }} role="img" aria-label="Rabattag">
    <g>
        <path d="M40 10 H90 L130 50 V110 L90 150 H40 L0 110 V50 Z" fill="#1E3FCB"/>
        <path d="M40 150 H90 L110 130 H60 Z" fill="#1EC77A"/>
        <path d="M110 130 L130 110 V90 Z" fill="#1EC77A"/>
        <circle cx="40" cy="55" r="9" fill="#ffffff"/>
        <circle cx="88" cy="103" r="9" fill="#ffffff"/>
        <rect x="35" y="95" width="60" height="12" rx="6" fill="#ffffff" transform="rotate(-45 65 100)"/>
    </g>
    <text x="165" y="105" font-family="'Poppins','Segoe UI',Arial,sans-serif" font-size="88" font-weight="800" fill="#1E3FCB">Rabatt<tspan fill="#1EC77A">ag</tspan></text>
</svg>
