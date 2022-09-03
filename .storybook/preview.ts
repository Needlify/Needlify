import { themes } from "@storybook/theming";
import "../assets/styles/global.scss";

export const parameters = {
    actions: { argTypesRegex: "^on[A-Z].*" },
    controls: {
        matchers: {
            color: /(background|color)$/i,
            date: /Date$/,
        },
    },
    darkMode: {
        dark: { ...themes.dark },
        light: { ...themes.light },
    },
};
