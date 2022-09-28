/* eslint-disable no-param-reassign */
/* eslint-disable @typescript-eslint/no-var-requires */
/* eslint-disable no-undef */
const Encore = require("@symfony/webpack-encore");

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
}

Encore.setOutputPath("public/build/")
    .setPublicPath("/build")
    // .setManifestKeyPrefix('build/')

    .configureFontRule({
        type: "asset",
    })

    .addEntries({
        /* Styles */
        "style:global": "./assets/styles/global.scss",
        "style:base": "./assets/styles/layout/base.scss",
        "style:header": "./assets/styles/layout/header.scss",
        "style:reset": "./assets/styles/reset.ts",

        /* Typescript files */

        /* Custom elements */
        "component:TimelineWrapper": "./assets/components/TimelineWrapper.ts",
        "component:TimelinePublication": "./assets/components/TimelinePublication.ts",
        "component:TimelineEvent": "./assets/components/TimelineEvent.ts",
        "component:TimelineThread": "./assets/components/TimelineThread.ts",
        "component:CurrentTime": "./assets/components/CurrentTime",
        "component:FeatherIcon": "./assets/components/FeatherIcon.ts",
        "component:TimeElapsed": "./assets/components/TimeElapsed.ts",
        "component:Tag": "./assets/components/Tag.ts",
    })

    .splitEntryChunks()

    .enableSassLoader()
    .enableTypeScriptLoader()
    // .enablePostCssLoader()
    .enableVueLoader()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .configureBabel(config => {
        config.plugins.push("@babel/plugin-proposal-class-properties");
    })

    .configureBabelPresetEnv(config => {
        config.useBuiltIns = "usage";
        config.corejs = 3;
    });

// uncomment to get integrity="..." attributes on your script & link tags
// requires WebpackEncoreBundle 1.4 or higher
// .enableIntegrityHashes(Encore.isProduction())

// eslint-disable-next-line no-undef
module.exports = Encore.getWebpackConfig();
