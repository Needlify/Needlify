import { HstVue } from "@histoire/plugin-vue";
import { defineConfig } from "histoire";

export default defineConfig({
    plugins: [HstVue()],
    setupFile: ".histoire/histoire.setup.ts",
    theme: {
        title: "Needlify - Stories",
        logo: {
            square: "/images/logo/icon-64.png",
            light: "/images/logo/icon-64.png",
            dark: "/images/logo/icon-64.png",
        },
        favicon: "images/logo/favicon.ico",
    },
});
