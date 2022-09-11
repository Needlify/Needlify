// eslint-disable-next-line @typescript-eslint/no-var-requires
const defaultTheme = require("tailwindcss/defaultTheme");

/* eslint-disable no-undef */
/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./assets/**/*.ts", "./templates/**/*.html.twig"],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Outfit", ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [],
};
