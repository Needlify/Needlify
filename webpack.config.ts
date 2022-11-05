/* eslint-disable no-param-reassign */
/* eslint-disable @typescript-eslint/no-var-requires */
const Encore = require("@symfony/webpack-encore");
const FosRouting = require("fos-router/webpack/FosRouting");

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
}

Encore.setOutputPath("public/build/")
    .setPublicPath("/build")
    // .setManifestKeyPrefix('build/')

    .configureFontRule({
        type: "asset",
    })

    .addPlugin(new FosRouting())

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge("./assets/controllers.json")

    .addEntries({
        /* Styles */
        "style:global": "./assets/styles/global.scss",
        "style:layout": "./assets/styles/layout/layout.scss",
        "style:layout-auth": "./assets/styles/layout/layout-auth.scss",
        "style:header": "./assets/styles/layout/header.scss",
        "style:reset": "./assets/styles/reset.ts",

        /* Page Style */
        "page:feed": "./assets/styles/pages/feed.scss",
        "page:classifier": "./assets/styles/pages/classifier.scss",
        "page:article": "./assets/pages/article.ts",
        "page:login": "./assets/styles/pages/login.scss",
        "page:dashboard": "./assets/styles/pages/admin/dashboard.scss",

        /* Typescript files */
        "file:layout": "./assets/files/layout.ts",
        "file:swup": "./assets/files/swup.ts",

        /* EasyAdmin custom style */
        "admin:thumbnail": "./assets/admin/thumbnail.scss",
        "admin:markdown:default": "./assets/admin/editor/markdown/markdown.ts",
        "admin:markdown:onlyText": "./assets/admin/editor/markdown/onlyText.scss",
        "admin:trix:default": "./assets/admin/editor/trix/default.scss",
        "admin:trix:onlyText": "./assets/admin/editor/trix/onlyText.scss",
        "admin:component:tags": "./assets/admin/component/tags.scss",
        "admin:component:publications": "./assets/admin/component/publications.scss",

        /* Custom Elements */
        "component:vue": "./assets/plugins/vue.ts",
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
    // .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    // .configureBabel(config => {})

    .configureBabelPresetEnv(config => {
        config.useBuiltIns = "usage";
        config.corejs = 3;
    });

// uncomment to get integrity="..." attributes on your script & link tags
// requires WebpackEncoreBundle 1.4 or higher
// .enableIntegrityHashes(Encore.isProduction())

// eslint-disable-next-line no-undef
module.exports = Encore.getWebpackConfig();
