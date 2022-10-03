<template>
    <div
        class="spinner-container"
        :id="id"
        :class="{ running: state === 'running', paused: state === 'paused' }"
        :style="`width: ${width}; height: ${height}; border: ${borderWidth} solid ${color};`"
    ></div>
</template>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    props: {
        id: {
            type: String,
            required: false,
            default: "spinner",
        },
        state: {
            type: String,
            required: false,
            default: "running",
            validator(value: string) {
                return ["running", "paused"].includes(value);
            },
        },
        width: {
            type: String,
            required: false,
            default: "22px",
        },
        height: {
            type: String,
            required: false,
            default: "22px",
        },
        color: {
            type: String,
            required: false,
            default: "black",
        },
        borderWidth: {
            type: String,
            required: false,
            default: "3px",
        },
    },
});
</script>

<style lang="scss" scoped>
.spinner-container {
    display: inline-block;
    vertical-align: middle;
    border-right-color: transparent !important;
    border-radius: 50%;
    animation: spinner-border 0.75s linear infinite;

    &.running {
        animation-play-state: running;
    }
    &.paused {
        animation-play-state: paused;
    }
}

@keyframes spinner-border {
    to {
        transform: rotate(360deg);
    }
}
</style>
