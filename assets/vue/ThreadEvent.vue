<template>
    <thread :icon="ThreadIcon[type].icon" :icon-color="ThreadIcon[type].color" :display-line="displayLine">
        <div class="event-content">
            <span class="actual-content" v-html="preview"></span>
            <span class="dot">•</span>
            <time-elapsed class="date" :date="publishedAt"></time-elapsed>
        </div>
    </thread>
</template>

<script lang="ts" setup>
import { ThreadIcon } from "../enum";
import { ThreadTypeVariation } from "../types";
import TimeElapsed from "./TimeElapsed.vue";
import Thread from "./Thread.vue";

defineProps<{
    type: ThreadTypeVariation;
    preview: string;
    publishedAt: string;
    displayLine: boolean;
}>();
</script>

<style lang="scss" scoped>
@import "../styles/mixins";
.event-content {
    line-height: 26px;
    color: var(--dark-soft);
    font-size: 16px;
    font-weight: 450;

    @include maxWidth(600px) {
        font-size: 14px;
        line-height: 20px;
        padding-top: 2px;

        & span:nth-child(1) {
            display: block;
        }
    }

    .actual-content ::v-deep(p) {
        margin: 0;
        display: inline-block;
    }

    .dot {
        padding: 0 6px;

        @include maxWidth(600px) {
            display: none;
        }
    }
}

.date {
    font-size: 14px;
    color: var(--light-dark);

    @include maxWidth(600px) {
        margin-bottom: 4px;
        font-size: 12px;
    }
}
</style>
