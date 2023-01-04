<template>
    <section id="timeline" v-if="total > 0">
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

    <div v-else-if="total === 0" id="empty-container">
        <img src="/images/empty-timeline.svg" alt="empty timeline image" />

        <p>Aucun contenu n'a été publié pour le moment</p>
    </div>

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

import type { Paginate, Thread } from "../types";

let cancelToken: CancelTokenSource = axios.CancelToken.source();
const firstQuery = ref(true);
const isLoading = ref(false);
const total = ref(-1);
const offset = ref(0);
const page = ref(1);
const threads = ref<Array<Thread>>([]);

const props = defineProps<{
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
    params.append("page", page.value.toString());

    if (props.id) {
        params.append("id", props.id);
        apiRoute = "api_get_publications";
    } else {
        apiRoute = "api_get_threads";
    }

    axios
        .get<Paginate<Thread>>(Routing.generate(apiRoute), {
            params,
            cancelToken: cancelToken.token,
        })
        .then(({ data }) => {
            offset.value += data.data.length;
            page.value += 1;
            if (firstQuery.value) {
                // on mounted
                firstQuery.value = false;
                total.value = data.pagination.total;
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
        if (window.pageYOffset + window.innerHeight >= document.body.scrollHeight - 1000 && !isLoading.value && offset.value < total.value) {
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

#empty-container {
    height: calc(100vh - 240px);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    opacity: 0.4;

    @include maxWidth(600px) {
        height: calc(100vh - 220px);
    }

    img {
        max-width: 50%;
        max-height: 50%;
        margin: 0 auto;
        display: block;
        // @include maxWidth(600px) {
        //     max-width: 50%;
        //     max-height: 50%;
        // }
    }

    p {
        margin-top: 50px;
        text-align: center;
        font-size: 18px;
        font-family: Jakarta;
        padding: 0 20px;
        color: var(--text);

        @include maxWidth(600px) {
            font-size: 14px;
        }
    }
}

#spinner-container {
    margin-top: 20px;
    display: flex;
    justify-content: center;
}
</style>
