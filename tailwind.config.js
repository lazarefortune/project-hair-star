const {colors: defaultColors} = require('tailwindcss/defaultTheme')

/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    content: [
        "./assets/**/*.js",
        "./templates/**/*.html.twig",
    ],
    theme: {
        extend: {
            borderWidth: {
                'thin': '.5px',
            },
            boxShadow: {
                'soft': 'rgba(0, 0, 0, 0.03) 0px 0px 0px 0.5px',
                'soft-md': 'rgba(0, 0, 0, 0.04) 0px 10px 32px',
                // 'soft-md': 'rgba(0, 0, 0, 0.04) 0px 5px 22px',
                // 'soft': '0 0 10px rgba(0, 0, 0, 0.05)',
                'soft-xs': '0 0 2px rgba(0, 0, 0, 0.05)',
                'soft-sm': '0 0 5px rgba(0, 0, 0, 0.05)',
                'soft-lg': '0 0 20px rgba(0, 0, 0, 0.05)',
                'soft-xl': '0 0 25px rgba(0, 0, 0, 0.05)',
                'soft-2xl': '0 0 30px rgba(0, 0, 0, 0.05)',
                'outline-primary': '0 0 0 3px rgba(132, 61, 255, 0.5)',
                'outline-secondary': '0 0 0 3px rgba(0, 0, 0, 0.5)',
            },
            fontSize: {
                'md': '0.938rem', // 15px
            },
            colors: {
                test: {
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
                },
                primary: {
                    "50": "rgb(248 250 252)",
                    "100": "rgb(241 243 245)",
                    "200": "rgb(226 232 240)",
                    "300": "rgb(203 213 225)",
                    "400": "rgb(148 163 184)",
                    "500": "rgb(100 116 139)",
                    "600": "rgb(71 85 105)",
                    "700": "rgb(51 65 85)",
                    "800": "rgb(30 41 59)",
                    "900": "rgb(15 23 42)",
                    "950": "rgb(2 6 23)"
                },
                danger: {
                    "50": "#fff8f8",
                    "100": "#ffefef",
                    "200": "#ffd7d7",
                    "300": "#ffafaf",
                    "400": "#ff7d7d",
                    "500": "#ff4a4a",
                    "600": "#ff1f1f",
                    "700": "#ff0b0b",
                    "800": "#e60000",
                    "900": "#c50000",
                    "950": "#8c0000"
                }
            }
        },
        fontFamily: {
            'inter': ['Inter Var'],
            'plusJakartaSans': ['Plus Jakarta Sans'],
            'ibmPlexSans': ['IBM Plex Sans'],
        }
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}

