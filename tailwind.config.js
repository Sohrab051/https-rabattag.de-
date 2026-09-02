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
                display: ['"Playfair Display"', 'Georgia', 'serif'],
            },
            colors: {
                primary: {
                    50: '#F5F3FF',
                    100: '#EAE3FB',
                    200: '#D3C2F5',
                    300: '#B497EA',
                    400: '#9370DB',
                    500: '#7C4FCB',
                    600: '#623AA6',
                    700: '#4B2A81',
                    800: '#341C5C',
                    900: '#1E0B36',
                },
                discount: {
                    50: '#FDF6E3',
                    100: '#FAEAC0',
                    200: '#F4D687',
                    300: '#EEC157',
                    400: '#F5B301',
                    500: '#D4A017',
                    600: '#B3830D',
                    700: '#8C660A',
                    800: '#5F4507',
                },
                urgent: {
                    50: '#FDF1F0',
                    100: '#FADCD9',
                    200: '#F2B0A9',
                    400: '#E8695A',
                    500: '#D9503F',
                    600: '#B93F30',
                    700: '#963226',
                },
                surface: {
                    light: '#FAF8FF',
                    dark: '#150826',
                },
                card: {
                    light: '#FFFFFF',
                    dark: '#1E1033',
                },
            },
            borderRadius: {
                card: '20px',
                btn: '9999px',
            },
            boxShadow: {
                card: '0 8px 30px -10px rgba(124, 58, 237, 0.20), 0 2px 8px -2px rgba(124, 58, 237, 0.12)',
                'card-hover': '0 16px 40px -12px rgba(124, 58, 237, 0.30), 0 4px 14px -2px rgba(212, 160, 23, 0.15)',
                glow: '0 0 60px 10px rgba(147, 112, 219, 0.25)',
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
