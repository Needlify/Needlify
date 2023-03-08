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
        "style_global": "./assets/styles/global.scss",
        "style_fonts": "./assets/styles/fonts.scss",
        "style_variables": "./assets/styles/variables.scss",
        "style_layout": "./assets/styles/layout/layout.scss",
        "style_layout-auth": "./assets/styles/layout/layout-auth.scss",
        "style_header": "./assets/styles/layout/header.scss",
        "style_footer": "./assets/styles/layout/footer.scss",
        "style_reset": "./assets/styles/reset.ts",
        "style_markdown": "./assets/styles/markdown.ts",
        "style_error": "./assets/styles/error.scss",

        /* Twig Component Style */
        "style_component_banner": "./assets/styles/components/twig/banner.scss",
        "style_component_breadcrumb": "./assets/styles/components/twig/breadcrumb.scss",
        "style_component_spinner": "./assets/styles/components/twig/spinner.scss",
        "style_component_tag": "./assets/styles/components/twig/tag.scss",
        "style_component_callout": "./assets/styles/components/callout.scss",

        /* Page Style */
        "page_feed": "./assets/styles/pages/feed.scss",
        "page_classifier": "./assets/styles/pages/classifier.scss",
        "page_article": "./assets/pages/article.ts",
        "page_login": "./assets/styles/pages/login.scss",
        "page_dashboard": "./assets/styles/pages/admin/dashboard.scss",
        "page_newsletter_register": "./assets/styles/pages/newsletter/register.scss",
        "page_newsletter_pending": "./assets/styles/pages/newsletter/pending.scss",
        "page_newsletter_completed": "./assets/styles/pages/newsletter/completed.scss",
        "page_newsletter_unsubscribed": "./assets/styles/pages/newsletter/unsubscribed.scss",
        "page_legal": "./assets/styles/pages/legal.scss",

        /* Typescript files */
        "file_layout": "./assets/files/layout.ts",
        "file_global": "./assets/files/global.ts",
        "file_swup": "./assets/files/swup.ts",

        /* EasyAdmin custom style */
        "admin_thumbnail": "./assets/admin/thumbnail.scss",
        "admin_markdown_default": "./assets/admin/editor/markdown/markdown.ts",
        "admin_markdown_onlyText": "./assets/admin/editor/markdown/onlyText.scss",
        "admin_select_dropdown": "./assets/admin/selectDropdown.scss",
        "admin_trix_default": "./assets/admin/editor/trix/default.scss",
        "admin_trix_onlyText": "./assets/admin/editor/trix/onlyText.scss",
        "admin_component_tags": "./assets/admin/component/tags.scss",

        /* Custom Elements */
        "component_vue": "./assets/plugins/vue.ts",
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
