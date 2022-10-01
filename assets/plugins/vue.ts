import { registerVueControllerComponents } from "@symfony/ux-vue";

registerVueControllerComponents(require.context("../vue", true, /\.vue$/, "sync"));
