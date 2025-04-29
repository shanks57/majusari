
/** @type {import('tailwindcss').Config} */

const defaultTheme = require("tailwindcss/defaultTheme");

export default {
    darkMode: "false",
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "node_modules/preline/dist/*.js",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
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
