<template>
    <div class="timeline-thread" :class="{ last: !displayLine }">
        <div class="timeline-icon-container" :style="{ 'background-color': iconColor }">
            <feather-icon :icon="icon" color="#ebeffd" stroke-width="2.5px" size="16px"></feather-icon>
        </div>
        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import FeatherIcon from "./FeatherIcon.vue";

defineProps({
    icon: {
        type: String,
        required: true,
    },
    iconColor: {
        type: String,
        required: true,
    },
    displayLine: {
        type: Boolean,
        default: true,
    },
});
</script>

<style lang="scss" scoped>
@import "../../styles/mixins.scss";
.timeline-thread {
    position: relative;

    &:not(.last)::before {
        content: "";
        position: absolute;
        width: 2px;
        height: calc(100% + 30px - 26px - 5px - 5px); /* 30px from row-gap on TimelineWrapper */
        top: calc(26px + 5px);
        left: -36px;
        background-color: var(--light-medium);

        @include maxWidth(600px) {
            left: -26px;
        }
    }

    .timeline-icon-container {
        position: absolute;
        top: 0px; /* Car border-top */
        left: -48px;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        box-sizing: content-box;

        @include maxWidth(600px) {
            left: -38px;
        }
    }
}
</style>
