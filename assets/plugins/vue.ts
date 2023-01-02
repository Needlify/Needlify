import { registerVueControllerComponents } from "@symfony/ux-vue";
import "./stimulus";

registerVueControllerComponents(require.context("../vue", true, /\.vue$/));
