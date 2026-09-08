import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                poppins: ['Poppins', 'sans-serif'],
            },
            colors: {
                primary: '#166534',
                'primary-light': '#f5f8f3',
                brand: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    500: '#16a34a',
                    600: '#15803d',
                    700: '#166534',
                    800: '#166534',
                    900: '#14532d',
                },
            },
        },
    },
    plugins: [forms],
};
