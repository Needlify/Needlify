module.exports = {
    stories: ["../assets/stories/**/*.stories.mdx", "../assets/stories/**/*.stories.ts"],
    addons: [
        "@storybook/addon-links",
        "@storybook/addon-essentials",
        "storybook-dark-mode",
        "@storybook/addon-a11y",
        "@storybook/preset-scss"
    ],
    framework: "@storybook/web-components",
    core: {
        builder: "@storybook/builder-vite", // 👈 The builder enabled here.
    },

};
