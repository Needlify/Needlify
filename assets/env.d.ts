/// <reference types="@histoire/plugin-vue/components" />

declare module "*.vue" {
    import { DefineComponent } from "vue";
    // eslint-disable-next-line @typescript-eslint/no-explicit-any, @typescript-eslint/ban-types
    const component: DefineComponent<{}, {}, any>;
    export default component;
}

declare module "*.scss";
declare module "@symfony/ux-vue";
declare module "@symfony/stimulus-bridge";