/// <reference types="vitest" />

import { defineConfig } from "vite";

export default () =>
    defineConfig({
        test: {
            environment: "jsdom",
            globals: true,
            coverage: {
                reporter: ["text", "json", "html"],
            },
        },
    });
