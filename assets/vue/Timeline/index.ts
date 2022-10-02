import { registerVueControllerComponents } from "@symfony/ux-vue";
import "../../plugins/stimulus";

registerVueControllerComponents(require.context("./", true, /\.vue$/, "sync"));
