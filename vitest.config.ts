/// <reference types="vitest" />

import { defineConfig } from "vite";

export default () =>
    defineConfig({
        test: {
            exclude: ["**/vendor/**", "**/node_modules/**"],
            environment: "jsdom",
            globals: true,
            coverage: {
                reporter: ["text"],
            },
        },
    });
