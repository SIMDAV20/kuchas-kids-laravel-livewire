const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors')

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // blue: "#163586",
                // green:"#A2CE04",
                blue: {
                    DEFAULT: "#163586",
                },
                green: {
                    DEFAULT: "#A2CE04",
                },
                pink: colors.pink,
                orange: colors.orange,
                greenLime: colors.lime,
                gray: colors.gray,
                indigo: colors.indigo,
                violet: colors.violet,
                gray: {
                    350: '#999999', // para el menu inicio
                    550: '#808080' // Para los textos
                },
                violet: {
                    150: '#E6E6F9', // para las franjas
                    350: '#BCBBE3' // textos seleccionados
                },
            },
            outline: {
                gray: "4px solid #D1D5DB",
            },
        },
    },

    variants: {
        extend: {
            opacity: ["disabled"],
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
