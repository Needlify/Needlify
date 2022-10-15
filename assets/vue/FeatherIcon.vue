<template>
    <div class="icon-container" :style="{ width: size, height: size }" v-html="correspondingIcon"></div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import * as feather from "feather-icons";

export default defineComponent({
    props: {
        icon: {
            type: String,
            default: "user",
        },
        color: {
            type: String,
            default: "black",
        },
        size: {
            type: String,
            default: "12px",
        },
        strokeWidth: {
            type: String,
            default: "2px",
        },
    },
    computed: {
        correspondingIcon(): string {
            return feather.icons[this.icon].toSvg({ "color": this.color, "width": this.size, "height": this.size, "stroke-width": this.strokeWidth });
        },
    },
});
</script>

<style lang="scss" scoped>
.icon-container {
    display: flex;
    align-items: center;
}
</style>
