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
        style_global: "./assets/styles/layout/global.scss",
        style_fonts: "./assets/styles/fonts.scss",
        style_variables: "./assets/styles/variables.scss",
        style_header: "./assets/styles/layout/header.scss",
        style_footer: "./assets/styles/layout/footer.scss",
        style_reset: "./assets/modules/libs/reset.ts",

        /* Layout */
        layout_base: "./assets/styles/layout/layout.scss",
        layout_auth: "./assets/styles/layout/layout-auth.scss",
        layout_error: "./assets/styles/layout/layout-error.scss",

        /* Modules */
        module_markdown: "./assets/modules/libs/markdown.ts",

        /* Twig Components */
        component_twig_banner: "./assets/components/twig/banner.scss",
        component_twig_breadcrumb: "./assets/components/twig/breadcrumb.scss",
        component_twig_spinner: "./assets/components/twig/spinner.scss",
        component_twig_tag: "./assets/components/twig/tag.scss",
        component_twig_callout: "./assets/components/markdown/callout.scss",

        /* Page Style */
        page_feed: "./assets/styles/pages/feed.scss",
        page_classifier: "./assets/styles/pages/classifier.scss",
        page_article: "./assets/styles/pages/article.scss",
        page_course: "./assets/styles/pages/course.scss",
        page_login: "./assets/styles/pages/login.scss",
        page_newsletter_register: "./assets/styles/pages/newsletter/register.scss",
        page_newsletter_pending: "./assets/styles/pages/newsletter/pending.scss",
        page_newsletter_completed: "./assets/styles/pages/newsletter/completed.scss",
        page_newsletter_unsubscribed: "./assets/styles/pages/newsletter/unsubscribed.scss",
        page_legal: "./assets/styles/pages/legal.scss",

        /* Partials Styles */
        partial_course_navigation: "./assets/styles/partials/_course_navigation.scss",

        /* Page Modules */
        module_page_document: "./assets/modules/pages/document.ts",
        module_page_privacy: "./assets/modules/pages/privacy.js",

        /* Plugins */
        plugin_power_glitch: "./assets/plugins/powerGlitch.ts",
        plugin_feather: "./assets/plugins/feather.ts",
        plugin_vue: "./assets/plugins/vue.ts",

        /* EasyAdmin custom style */
        admin_page_dashboard: "./assets/styles/pages/admin/dashboard.scss",
        admin_page_course_form: "./assets/admin/course/courseForm.ts",
        admin_form_override_thumbnail: "./assets/admin/thumbnail.scss",
        admin_form_override_select: "./assets/admin/selectDropdown.scss",
        admin_editor_markdown_default: "./assets/admin/editor/markdown/markdown.ts",
        admin_editor_markdown_onlyText: "./assets/admin/editor/markdown/onlyText.scss",
        admin_editor_trix_default: "./assets/admin/editor/trix/default.scss",
        admin_editor_trix_onlyText: "./assets/admin/editor/trix/onlyText.scss",
        admin_component_tags: "./assets/admin/component/tags.scss",
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
