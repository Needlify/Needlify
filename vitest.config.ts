/// <reference types="vitest" />

import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

export default () =>
    defineConfig({
        plugins: [vue()],
        test: {
            exclude: ["**/vendor/**", "**/node_modules/**"],
            environment: "jsdom",
            globals: true,
            coverage: {
                reporter: ["text"],
            },
        },
    });
