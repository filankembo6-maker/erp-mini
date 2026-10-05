import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'bisaleli-blue': '#1e40af',
                'bisaleli-cyan': '#22d3ee',
                'bisaleli-green': '#10b981',
                'bisaleli-light': '#f0f9ff',
            },
            animation: {
                'fade-in': 'fadeIn 0.8s ease-out forwards',
                'fade-in-delay': 'fadeIn 0.8s ease-out 0.5s forwards',
                'fade-in-delay-2': 'fadeIn 0.8s ease-out 1s forwards',
                'scale-in': 'scaleIn 0.8s ease-out forwards',
                'pulse-slow': 'pulseSlow 3s ease-in-out infinite',
                'fade-out': 'fadeOut 0.5s ease-in forwards',
                'slide-up': 'slideUp 0.7s ease-out forwards',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeOut: {
                    '0%': { opacity: '1' },
                    '100%': { opacity: '0' },
                },
                scaleIn: {
                    '0%': { opacity: '0', transform: 'scale(0.7)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                },
                pulseSlow: {
                    '0%, 100%': { opacity: '0.3', transform: 'scale(1)' },
                    '50%': { opacity: '0.5', transform: 'scale(1.08)' },
                },
                slideUp: {
                    '0%': { opacity: '0', transform: 'translateY(30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
        },
    },

    plugins: [forms],
};