import Swup from "swup";
import SwupProgressPlugin from "@swup/progress-plugin";
import SwupHeadPlugin from "@swup/head-plugin";
import "../styles/swup.scss";

// eslint-disable-next-line no-unused-vars, @typescript-eslint/no-unused-vars
const swup = new Swup({
    plugins: [
        new SwupProgressPlugin({
            className: "swup-progress-bar",
            transition: 300,
            delay: 300,
            initialValue: 0.25,
            hideImmediately: true,
        }),
        new SwupHeadPlugin(),
    ],
    containers: ["#main-container"],
    cache: false, // TODO Checker la pertinence de cette option
});
