/* eslint-disable @typescript-eslint/no-var-requires */
/* eslint-disable no-undef */
const Encore = require("@symfony/webpack-encore");

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
}

Encore.setOutputPath("public/build/")
    .setPublicPath("/build")
    // .setManifestKeyPrefix('build/')

    .addEntries({
        /* Styles */
        global: "./assets/styles/global.scss",

        /* Typescript files */

        /* Lit components */
        SimpleGreeting: "./assets/components/SimpleGreeting.ts",
    })

    .splitEntryChunks()

    .enableSassLoader()
    .enableTypeScriptLoader()
    .enablePostCssLoader()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .configureBabel((config) => {
        config.plugins.push("@babel/plugin-proposal-class-properties");
    })

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = "usage";
        config.corejs = 3;
    });

// uncomment to get integrity="..." attributes on your script & link tags
// requires WebpackEncoreBundle 1.4 or higher
// .enableIntegrityHashes(Encore.isProduction())

// eslint-disable-next-line no-undef
module.exports = Encore.getWebpackConfig();
