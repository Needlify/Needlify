import { startStimulusApp } from "@symfony/stimulus-bridge";

startStimulusApp(require.context("@symfony/stimulus-bridge/lazy-controller-loader!../controllers", true, /\.[jt]sx?$/));
