import { PowerGlitch } from "powerglitch";
import * as feather from "feather-icons";

PowerGlitch.glitch(".glitch", {
    playMode: "hover",
    createContainers: true,
    hideOverflow: false,
    timing: {
        duration: 250,
        iterations: 1,
        easing: "ease-in-out",
    },
    glitchTimeSpan: {
        start: 0,
        end: 1,
    },
    shake: {
        velocity: 20,
        amplitudeX: 0.2,
        amplitudeY: 0.2,
    },
    slice: {
        count: 20,
        velocity: 10,
        minHeight: 0.03,
        maxHeight: 0.03,
        hueRotate: true,
    },
});

feather.replace();
