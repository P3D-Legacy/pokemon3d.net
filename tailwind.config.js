const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    mode: 'jit',
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/power-components/livewire-powergrid/resources/views/**/*.blade.php',
        './vendor/power-components/livewire-powergrid/src/Themes/Tailwind.php',
    ],

    darkMode: 'class',
    
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            backgroundImage: theme => ({
                'spring': "url('/img/spring.png')",
            }),
            colors: {
                'gamejolt-green': '#ccff00',
            }
        },
        debugScreens: {
            position: ['top', 'left'],
        },
    },

    plugins: [
        require("@tailwindcss/forms")({
            strategy: 'class',
        }),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
        require('tailwindcss-debug-screens'),
    ],
};
