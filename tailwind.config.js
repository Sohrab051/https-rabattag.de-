import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Manrope', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                },
                discount: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                },
                urgent: {
                    50: '#fff7ed',
                    100: '#ffedd5',
                    200: '#fed7aa',
                    400: '#fb923c',
                    500: '#f97316',
                    600: '#ea580c',
                    700: '#c2410c',
                },
                surface: {
                    light: '#f8fafc',
                    dark: '#0f172a',
                },
                card: {
                    light: '#ffffff',
                    dark: '#1e293b',
                },
            },
            borderRadius: {
                card: '10px',
                btn: '8px',
            },
            boxShadow: {
                card: '0 1px 3px 0 rgb(0 0 0 / 0.07), 0 4px 16px 0 rgb(0 0 0 / 0.06)',
                'card-hover': '0 4px 12px 0 rgb(0 0 0 / 0.10), 0 8px 24px 0 rgb(0 0 0 / 0.08)',
            },
            keyframes: {
                'pulse-urgent': {
                    '0%, 100%': { opacity: 1 },
                    '50%': { opacity: 0.75 },
                },
                'reveal-code': {
                    from: { letterSpacing: '-0.5em', opacity: 0 },
                    to: { letterSpacing: '0.1em', opacity: 1 },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
            },
            animation: {
                'pulse-urgent': 'pulse-urgent 2s ease-in-out infinite',
                'reveal-code': 'reveal-code 0.4s ease-out forwards',
                shimmer: 'shimmer 1.5s infinite',
            },
        },
    },

    plugins: [forms],
};
