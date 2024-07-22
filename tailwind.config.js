/** @type {import('tailwindcss').Config} */

const defaultTheme = require("tailwindcss/defaultTheme");
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                inter: ["Inter var", "sans-serif"],
                sans: ["Manrope", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                
            }
        },
    },
    plugins: [],
};
