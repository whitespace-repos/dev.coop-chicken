const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './src/**/*.{html,js}'
    ],

    theme: {
        container: {
            center: true,
        },
        neumorphismColor: {
            dark: {
                shadow: '#202123',
            // ...
            },
        },
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                transparent: 'transparent',
                'primary': '#202123',
                'danger' : '#ff2e00',
                'info' : '#bf8a00',
                'org-border-color':'rgba(0, 0, 0, 0.4)',
                'green': '#09b94f',
                'org-gray':'#f3f3f3',
            }
        },
    },

    plugins: [
                require('@tailwindcss/forms'),
                require('tailwindcss-neumorphism')
            ],
};
