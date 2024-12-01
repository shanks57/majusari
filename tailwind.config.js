/** @type {import('tailwindcss').Config} */

const defaultTheme = require("tailwindcss/defaultTheme");
export default {
    darkMode: "false",
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "node_modules/preline/dist/*.js",
    ],
    theme: {
        extend: {
            fontFamily: {
                inter: ["Inter var", "sans-serif"],
                sans: ["Manrope", ...defaultTheme.fontFamily.sans],
            },
            colors: {},
        },
    },
    plugins: [require("preline/plugin"), require("@tailwindcss/forms")],
};
