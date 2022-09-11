module.exports = {
    stories: ["../assets/stories/**/*.stories.mdx", "../assets/stories/**/*.stories.ts"],
    addons: [
        "@storybook/addon-links",
        "@storybook/addon-essentials",
        "storybook-dark-mode",
        "@storybook/addon-a11y",
        {
            name: "@storybook/addon-postcss",
            options: {
                cssLoaderOptions: {
                    importLoaders: 1,
                },
                postcssLoaderOptions: {
                    implementation: require("postcss"),
                },
            },
        },
    ],
    framework: "@storybook/web-components",
    core: {
        builder: "@storybook/builder-vite", // 👈 The builder enabled here.
    },
};
