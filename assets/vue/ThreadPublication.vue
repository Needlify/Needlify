<template>
    <thread :icon="ThreadIcon[type].icon" :icon-color="ThreadIcon[type].color" :display-line="displayLine">
        <div class="event-content in-publication">
            <span>
                New
                <span v-if="type === ThreadTypeVariationEnum.COURSE">course</span>
                <span v-else>publication</span>
                in the topic
                <strong>
                    <a :href="generateTopicUrl(topic.slug)">{{ topic.name }}</a>
                </strong>
            </span>
        </div>

        <div class="publication-content" :class="{ course: type === ThreadTypeVariationEnum.COURSE }">
            <h3 v-if="slug">
                <a :href="generateUrl(type, slug)">{{ title }}</a>
            </h3>

            <time-elapsed class="date" :date="publishedAt"></time-elapsed>

            <div class="tag-container" v-show="tags.length > 0">
                <x-tag :name="tag.name" :slug="tag.slug" v-for="(tag, indexTag) in tags" :key="indexTag" />
            </div>

            <p v-if="ThreadTypeVariationEnum.DOCUMENT.includes(type)" class="preview with-max-line">{{ preview }}</p>
            <span v-else class="preview" v-html="preview"></span>
        </div>
    </thread>
</template>

<script setup lang="ts">
import Routing from "fos-router";
import TimeElapsed from "./TimeElapsed.vue";
import { ThreadIcon, ThreadTypeVariationEnum } from "../enum";
import { Tag, ThreadTypeVariation, Topic } from "../types";
import XTag from "./Tag.vue";
import Thread from "./Thread.vue";

defineProps<{
    type: ThreadTypeVariation;
    slug?: string;
    preview: string;
    title?: string;
    publishedAt: string;
    tags: Array<Tag>;
    topic: Topic;
    displayLine: boolean;
}>();

const generateTopicUrl = (slug: string) => Routing.generate("app_topic", { slug });

const generateUrl = (type: string, slug: string) => {
    switch (type) {
        case ThreadTypeVariationEnum.COURSE:
            return Routing.generate("app_course", { slug });
        case ThreadTypeVariationEnum.ARTICLE:
            return Routing.generate("app_article", { slug });
        default:
            throw Error("Unknown thread type");
    }
};
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

        & > span:nth-child(1) {
            display: block;
        }
    }

    &.in-publication {
        margin-bottom: 10px;
        @include maxWidth(600px) {
            margin-bottom: 6px;
        }
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

.publication-content {
    background-color: var(--white);
    border: 2px solid var(--light-soft);
    border-radius: 16px;
    padding: 22px;
    display: flex;
    flex-direction: column;
    row-gap: 16px;
    position: relative;

    @include maxWidth(600px) {
        padding: 16px;
        border-radius: 6px;
        row-gap: 10px;
    }

    &.course::before {
        content: "";
        position: absolute;
        background-color: var(--light-very-soft);
        border: 2px solid var(--light-light);
        border-radius: 16px;
        width: calc(100% - 2 * 20px);
        height: 20%;
        bottom: -8px;
        left: 20px;
        z-index: -1;
        @include maxWidth(600px) {
            border-radius: 6px;
            width: calc(100% - 2 * 10px);
            bottom: -6px;
            left: 10px;
        }
    }
    &.course::after {
        content: "";
        position: absolute;
        background-color: var(--light-soft);
        border: 2px solid var(--light-light);
        border-radius: 16px;
        width: calc(100% - 2 * 40px);
        height: 20%;
        bottom: -14px;
        left: 40px;
        z-index: -2;
        @include maxWidth(600px) {
            border-radius: 6px;
            width: calc(100% - 2 * 20px);
            bottom: -10px;
            left: 20px;
        }
    }

    h3 {
        margin: 0;
        display: block;
        line-height: 26px;

        @include maxWidth(600px) {
            font-size: 16px;
        }

        a {
            color: var(--dark-light);
            text-decoration: none;
            transition: color 200ms ease-in-out;

            &:hover {
                color: var(--primary);
            }
        }
    }

    .preview {
        line-height: 24px;
        color: var(--dark-very-soft);
        font-size: 16px;
        font-weight: 450;
        margin: 0;

        text-overflow: ellipsis;
        overflow: hidden;
        display: -webkit-box !important;
        -webkit-box-orient: vertical;
        white-space: normal;

        ::v-deep(p) {
            margin: 0;
            display: inline-block;
        }

        &.with-max-line {
            -webkit-line-clamp: var(--preview-max-lines);
        }

        @include maxWidth(600px) {
            font-size: 12px;
            line-height: 18px;
        }
    }

    .tag-container {
        display: flex;
        column-gap: 10px;
        row-gap: 6px;
        flex-wrap: wrap;

        @include maxWidth(600px) {
            column-gap: 6px;
            row-gap: 4px;
        }
    }
}
</style>
