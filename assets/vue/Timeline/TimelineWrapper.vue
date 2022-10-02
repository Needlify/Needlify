<template>
    <section id="timeline">
        <div v-for="(thread, index) in threads" :key="index">
            <!-- Event -->
            <timeline-thread v-if="thread.type === 'event'" :icon="ThreadIcon[thread.type].icon" :icon-color="ThreadIcon[thread.type].color" :displayLine="index !== total - 1">
                <div class="event-content">
                    <span v-html="thread.preview"></span>
                    <span class="dot">•</span>
                    <time-elapsed class="date" :date="thread.publishedAt"></time-elapsed>
                </div>
            </timeline-thread>

            <!-- Publication -->
            <timeline-thread v-else :icon="ThreadIcon[thread.type].icon" :icon-color="ThreadIcon[thread.type].color" :displayLine="index !== total - 1">
                <div class="event-content in-publication">
                    <span>
                        Nouvelle publication dans le topic <a :href="generateUrl(thread.topic.slug)">{{ thread.topic.name }}</a>
                    </span>
                </div>

                <div class="publication-content">
                    <h3 v-if="thread.type === ThreadTypeVariationEnum.ARTICLE">
                        <a :href="generateUrl(thread.slug)">{{ thread.title }}</a>
                    </h3>

                    <time-elapsed class="date" :date="thread.publishedAt"></time-elapsed>

                    <div class="tag-container" v-show="thread.tags.length > 0">
                        <tag :name="tag.name" :slug="tag.slug" v-for="(tag, index) in thread.tags" :key="index"></tag>
                    </div>

                    <p class="description" :class="{ 'with-max-line': thread.type === ThreadTypeVariationEnum.ARTICLE }">{{ thread.preview }}</p>
                </div>
            </timeline-thread>
        </div>
    </section>

    <div id="spinner-container" v-show="isLoading">
        <spinner color="#8a9399"></spinner>
    </div>
</template>

<script setup lang="ts">
import axios, { CancelTokenSource } from "axios";
import { onMounted, onUpdated, ref } from "vue";
import Routing from "fos-router";

import TimelineThread from "./TimelineThread.vue";
import Spinner from "../Spinner/Spinner.vue";
import Tag from "../Tag/Tag.vue";
import TimeElapsed from "../TimeElapsed/TimeElapsed.vue";

import { ThreadIcon, ThreadTypeVariationEnum } from "../../enum";
import type { ThreadsQuery, Thread } from "../../types.d";

let cancelToken: CancelTokenSource = axios.CancelToken.source();
const firstQuery = ref(true);
const isLoading = ref(false);
const total = ref(-1);
const offset = ref(0);
const threads = ref<Array<Thread>>([]);

const updateFeed = () => {
    if (cancelToken !== undefined) {
        cancelToken.cancel("Operation cancel due to new request");
    }
    cancelToken = axios.CancelToken.source();
    isLoading.value = true;
    const params = new URLSearchParams();
    params.append("offset", offset.value.toString());
    axios
        .get<ThreadsQuery>("/api/rest/threads", {
            params,
            cancelToken: cancelToken.token,
        })
        .then(({ data }) => {
            offset.value += data.data.length;
            if (firstQuery.value) {
                // on mounted
                firstQuery.value = false;
                total.value = data.total;
            }
            threads.value = threads.value.concat(data.data);
            isLoading.value = false;
        })
        .catch(err => {
            if (axios.isCancel(err)) {
                console.log(`Cancelling previous request: ${err.message}`);
            }
        });
};

onMounted(() => {
    updateFeed();
    window.addEventListener("scroll", () => {
        if (window.innerHeight + window.pageYOffset >= document.body.offsetHeight - 800 && !isLoading.value && offset.value < total.value) {
            updateFeed();
        }
    });
});

onUpdated(() => {
    console.log(offset.value, total.value);
});

// TODO Changer l'url
const generateUrl = () => Routing.generate("app_home");
</script>

<style lang="scss" scoped>
@import "../../styles/mixins";

#timeline {
    width: calc(100% - 50px);
    padding-left: 50px;
    position: relative;
    display: flex;
    flex-direction: column;
    row-gap: 30px;

    @include maxWidth(600px) {
        width: calc(100% - 40px);
        padding-left: 40px;
        row-gap: 20px;
    }
}

#spinner-container {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}

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
        // &:not(.in-publication) span:nth-child(1) {
        //     margin-bottom: -5px;
        // }
    }

    &.in-publication {
        margin-bottom: 10px;
        @include maxWidth(600px) {
            margin-bottom: 6px;
        }
    }

    a {
        color: var(--primary);
        text-decoration: none;
        transition: color 200ms ease-in-out;
    }

    a:hover {
        color: var(--dark);
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

    @include maxWidth(600px) {
        padding: 16px;
        border-radius: 6px;
        row-gap: 10px;
    }

    h3 {
        margin: 0;
        display: block;

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

    .description {
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
