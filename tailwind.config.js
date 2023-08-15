const {colors: defaultColors} = require('tailwindcss/defaultTheme')

const colors = {
    ...defaultColors,
    ...{
        primary: {
            "50": "#f3f1ff",
            "100": "#ebe5ff",
            "200": "#d9ceff",
            "300": "#bea6ff",
            "400": "#9f75ff",
            "500": "#843dff",
            "600": "#7916ff",
            "700": "#6b04fd",
            "800": "#5a03d5",
            "900": "#4b05ad",
            "950": "#2c0076"
        }
    },
}


/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    content: [
        "./assets/**/*.js",
        "./templates/**/*.html.twig",
    ],
    theme: {
        extend: {
            boxShadow: {
                'soft': 'rgba(0, 0, 0, 0.03) 0px 0px 0px 0.5px',
                'soft-md': 'rgba(0, 0, 0, 0.04) 0px 5px 22px',
                // 'soft': '0 0 10px rgba(0, 0, 0, 0.05)',
                'soft-xs': '0 0 2px rgba(0, 0, 0, 0.05)',
                'soft-sm': '0 0 5px rgba(0, 0, 0, 0.05)',
                'soft-lg': '0 0 20px rgba(0, 0, 0, 0.05)',
                'soft-xl': '0 0 25px rgba(0, 0, 0, 0.05)',
                'soft-2xl': '0 0 30px rgba(0, 0, 0, 0.05)',
                'outline-primary': '0 0 0 3px rgba(132, 61, 255, 0.5)',
                'outline-secondary': '0 0 0 3px rgba(0, 0, 0, 0.5)',
            },
            colors: {
                primary: {
                    "50": "#f3f1ff",
                    "100": "#ebe5ff",
                    "200": "#d9ceff",
                    "300": "#bea6ff",
                    "400": "#9f75ff",
                    "500": "#843dff",
                    "600": "#7916ff",
                    "700": "#6b04fd",
                    "800": "#5a03d5",
                    "900": "#4b05ad",
                    "950": "#2c0076"
                }
            }
        },
        fontFamily: {
            'inter': ['Inter'],
            'josefin': ['JosefinSans'],
            'pacifico': ['Pacifico'],
            'lato': ['Lato'],
            'poppins': ['Poppins'],
            'circularStd': ['CircularStd'],
        }
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}

