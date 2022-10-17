import { describe, expect, test } from "vitest";
import { mount } from "@vue/test-utils";
import TimeElapsed from "../vue/TimeElapsed.vue";

describe("Import vue component", () => {
    test("normal imports as expected", async () => {
        const cmp = await import("../vue/TimeElapsed.vue");
        expect(cmp).toBeDefined();
    });

    test("dynamic imports as expected", async () => {
        const name = "TimeElapsed";
        const cmp = await import(`../vue/${name}.vue`);
        expect(cmp).toBeDefined();
    });
});

describe("Features", () => {
    test("Test component feature", () => {
        const date = "2020-09-09T20:42:34+00:00";

        const wrapper = mount(TimeElapsed, {
            props: {
                date,
            },
        });

        expect(wrapper.text()).not.toBeNull();
    });
});
