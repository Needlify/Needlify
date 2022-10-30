<template>
    <section id="timeline">
        <div v-for="(thread, index) in threads" :key="index">
            <!-- Event -->
            <thread-event v-if="thread.type === 'event'" :type="thread.type" :preview="thread.preview" :published-at="thread.publishedAt" :display-line="index !== total - 1" />

            <!-- Publication (Article & Moodline) -->
            <thread-publication
                v-else
                :type="thread.type"
                :slug="thread.slug"
                :preview="thread.preview"
                :title="thread.title"
                :published-at="thread.publishedAt"
                :tags="thread.tags"
                :topic="thread.topic"
                :display-line="index !== total - 1"
            />
        </div>
    </section>

    <div id="spinner-container" v-show="isLoading">
        <spinner color="#8a9399"></spinner>
    </div>
</template>

<script setup lang="ts">
import axios, { CancelTokenSource } from "axios";
import { onMounted, ref } from "vue";
import Routing from "fos-router";

import Spinner from "./Spinner.vue";
import ThreadEvent from "./ThreadEvent.vue";
import ThreadPublication from "./ThreadPublication.vue";

import type { ThreadsQuery, Thread, ClassifierTypeVariation } from "../types";

let cancelToken: CancelTokenSource = axios.CancelToken.source();
const firstQuery = ref(true);
const isLoading = ref(false);
const total = ref(-1);
const offset = ref(0);
const threads = ref<Array<Thread>>([]);

const props = defineProps<{
    selector?: ClassifierTypeVariation;
    id?: string;
}>();

const updateFeed = () => {
    if (cancelToken !== undefined) {
        cancelToken.cancel("Operation cancel due to new request");
    }
    cancelToken = axios.CancelToken.source();
    isLoading.value = true;

    let apiRoute;
    const params = new URLSearchParams();
    params.append("offset", offset.value.toString());

    if (props.selector && props.id) {
        params.append("selector", props.selector);
        params.append("id", props.id);
        apiRoute = "api_get_publications";
    } else {
        apiRoute = "api_get_threads";
    }

    axios
        .get<ThreadsQuery>(Routing.generate(apiRoute), {
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
</script>

<style lang="scss" scoped>
@import "../styles/mixins";

#timeline {
    width: 100%;
    padding-left: 50px;
    position: relative;
    display: flex;
    flex-direction: column;
    row-gap: 30px;

    @include maxWidth(600px) {
        width: 100%;
        padding-left: 40px;
        row-gap: 20px;
    }
}

#spinner-container {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}
</style>
