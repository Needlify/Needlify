import { registerVueControllerComponents } from "@symfony/ux-vue";
import "./stimulus";

registerVueControllerComponents(require.context("../components/vue", true, /\.vue$/));
