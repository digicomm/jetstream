import plugin from 'tailwindcss/plugin'
import defaultTheme from 'tailwindcss/defaultTheme';
import colors from 'tailwindcss/colors';

import aspectRatio from '@tailwindcss/aspect-ratio';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import headlessui from '@headlessui/tailwindcss';


/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/**/*.vue',
        './resources/js/**/**/**/*.vue',
        './resources/js/**/*.js',
        './resources/js/**/*.js',
        './resources/js/**/*.ts',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter Tight", ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                xxs: '0.69rem'
            },
            width: {
                'xs': '20rem',
                'sm': '24rem',
                'md': '28rem',
                'lg': '32rem',
                'xl': '36rem',
                '2xl': '42rem',
                '3xl': '48rem',
                '4xl': '56rem',
                '5xl': '64rem',
                '6xl': '72rem',
                '7xl': '80rem',
            },
            maxWidth: {
                '8xl': '90rem',
                '9xl': '105rem',
                '10xl': '120rem',
                '95p': '95%',
                '99p': '99%',
            },
            zIndex: {
                1: 1,
                60: 60,
                70: 70,
                80: 80,
                90: 90,
                100: 100,
            },
            boxShadow: {
                glow: '0 0 4px rgb(0 0 0 / 0.1)',
            },
            opacity: {
                1: '0.01',
                2.5: '0.025',
                7.5: '0.075',
                15: '0.15',
                35: '0.35',
                45: '0.45',
                55: '0.55',
                65: '0.65',
                85: '0.85',
            },
            colors: {
                'digicomm': {
                    50: '#ebfef7',
                    100: '#d0fbe9',
                    200: '#a4f6d7',
                    300: '#6aebc3',
                    400: '#2fd8a9',
                    500: '#0abf93',
                    600: '#009b78',
                    700: '#007961',
                    800: '#03624f',
                    900: '#045043',
                    950: '#012d27',
                }
            },
            typography: {
                DEFAULT: {
                    css: {
                        a: {
                            textDecoration: 'none',
                            '&:hover': {
                                opacity: '.75',
                            },
                        },
                        img: {
                            borderRadius: defaultTheme.borderRadius.lg,
                        },
                    },
                },
            },
        },
    },
    plugins: [
        aspectRatio,
        forms,
        headlessui,
        typography,
        plugin(function ({addUtilities}) {
            const utilFormSwitch = {
                ".form-switch": {
                    border: "transparent",
                    "background-color": colors.gray[300],
                    "background-image":
                        "url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e\")",
                    "background-position": "left center",
                    "background-repeat": "no-repeat",
                    "background-size": "contain !important",
                    "vertical-align": "top",
                    "&:checked": {
                        border: "transparent",
                        "background-color": "currentColor",
                        "background-image":
                            "url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e\")",
                        "background-position": "right center",
                    },
                    "&:disabled, &:disabled + label": {
                        opacity: ".5",
                        cursor: "not-allowed",
                    },
                },
            };

            addUtilities(utilFormSwitch);

        }),
    ],
    darkMode: ['class', '[data-mode="dark"]'],
    safelist: [
        {
            pattern: /^p(\w?)-/,
            variants: ['sm', 'md', 'lg', 'xl', '2xl', 'hover']
        },
        {
            pattern: /^gap-/,
            variants: ['sm', 'md', 'lg', 'xl', '2xl', 'hover']
        },
        {
            pattern: /^space-/,
            variants: ['sm', 'md', 'lg', 'xl', '2xl', 'hover']
        },
        {
            pattern: /^grid-cols-/,
            variants: ['sm', 'md', 'lg', 'xl', '2xl', 'hover']
        },
        {
            pattern: /^col-span-/,
            variants: ['sm', 'md', 'lg', 'xl', '2xl', 'hover']
        },
    ]

};
