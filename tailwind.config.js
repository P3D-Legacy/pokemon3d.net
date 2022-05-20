const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    mode: 'jit',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/power-components/livewire-powergrid/resources/views/**/*.blade.php',
        './vendor/power-components/livewire-powergrid/src/Themes/Tailwind.php',
        './vendor/wireui/wireui/resources/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            backgroundImage: {
                spring: "url('/img/spring.png')",
                summer: "url('/img/summer.png')",
                fall: "url('/img/fall.png')",
                winter: "url('/img/winter.png')",
            },
            colors: {
                'gamejolt-green': '#ccff00',
                'gamejolt-dark-green': '#2f7f6f',
            },
        },
        debugScreens: {
            position: ['top', 'right'],
        },
    },
    presets: [require('./vendor/wireui/wireui/tailwind.config.js')],
    plugins: [
        require('@tailwindcss/forms')({
            strategy: 'class',
        }),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
        require('tailwindcss-debug-screens'),
        require('tw-elements/dist/plugin'),
    ],
};
