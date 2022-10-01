<template>
    <div class="timeline-thread" :class="{ last: !displayLine }">
        <div class="timeline-icon-container" :style="{ 'background-color': iconColor }">
            <feather-icon :icon="icon" color="#ebeffd" strokeWidth="2.5px" size="16px"></feather-icon>
        </div>
        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import "../../components/FeatherIcon";

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
@import "../../styles/mixins";

.timeline-thread {
    position: relative;

    &:not(.last)::before {
        content: "";
        position: absolute;
        width: 2px;
        height: calc(100% + 30px); /* 30px from row-gap on TimelineWrapper */
        top: 0px;
        left: -36px;
        background-color: var(--light-medium);

        @include maxWidth(600px) {
            left: -26px;
        }
    }

    .timeline-icon-container {
        position: absolute;
        top: -6px; /* Car border-top */
        left: -48px;
        width: 26px;
        aspect-ratio: 1;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        border-top: 6px solid var(--light);
        border-bottom: 6px solid var(--light);

        @include maxWidth(600px) {
            left: -38px;
        }
    }
}
</style>
