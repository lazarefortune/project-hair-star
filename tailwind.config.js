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
            animation: {
                'fade-in': 'fadeIn 0.4s ease-in-out',
                'fade-in-left': 'fadeInRight 0.4s ease-in-out',
            },
            keyframes: {
                fadeIn: {
                    '0%': {
                        opacity: '0',
                        transform: 'translateY(10px)',
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateY(0)',
                    },
                },
                fadeInRight: {
                    '0%': {
                        opacity: '0',
                        transform: 'translateX(10px)',
                    },
                    '100%': {
                        opacity: '1',
                        transform: 'translateX(0)',
                    },
                },
            },
            borderWidth: {
                'thin': '.5px',
            },
            boxShadow: {
                'custom': '0 2px 4px #d8e1e8',
                'md': '0 2px 4px #d8e1e8',
                // 'soft': 'rgba(0, 0, 0, 0.03) 0px 0px 0px 0.5px',
                'soft': '0 2px 4px #d8e1e8',
                'soft-md': '0 2px 4px #d8e1e8',
                // 'soft-md': 'rgba(0, 0, 0, 0.04) 0px 10px 32px',
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
                },
                primaryOld: {
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
                secondary: {
                    "50": "#f8f9fc",
                    "100": "#f1f3f8",
                    "200": "#e9eef5",
                    "300": "#d8e1ef",
                    "400": "#b6c9e1",
                    "500": "#93b1d3",
                    "600": "#7c9ac0",
                    "700": "#657ea8",
                    "800": "#4e628f",
                    "900": "#3d4e74",
                    "950": "#2b3650"
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

