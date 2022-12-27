<template>
    <section id="timeline" v-if="totalPages > 0">
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

    <div v-else-if="totalPages === 0" id="empty-container">
        <img src="/images/empty-timeline.svg" alt="empty timeline image" />

        <p>Aucun contenu n'a été publié pour le moment</p>
    </div>

    <div id="spinner-container" v-show="isLoading">
        <spinner color="#8a9399"></spinner>
    </div>
</template>

<script lang="ts">
import axios, { CancelTokenSource } from "axios";
import { ref, defineComponent, PropType } from "vue";
import Routing from "fos-router";

import Spinner from "./Spinner.vue";
import ThreadEvent from "./ThreadEvent.vue";
import ThreadPublication from "./ThreadPublication.vue";

import type { Paginate, Thread } from "../types";

export default defineComponent({
    components: {
        Spinner,
        ThreadEvent,
        ThreadPublication,
    },
    props: {
        id: {
            type: String as PropType<string | undefined>,
            required: false,
            default: undefined,
        },
    },
    data: () => ({
        isLoading: true,
        page: 1,
        totalPages: -1,
        total: -1,
        threads: [] as Array<Thread>,
    }),
    setup() {
        const cancelToken = ref<CancelTokenSource>(axios.CancelToken.source());
        return {
            cancelToken,
        };
    },
    mounted() {
        this.updateFeed();
        window.addEventListener("scroll", this.onScrollEvent);
    },
    beforeUnmount() {
        window.removeEventListener("scroll", this.onScrollEvent);
    },
    methods: {
        onScrollEvent() {
            if (this.isBottomReached && !this.isLoading && this.page !== this.totalPages && this.totalPages !== -1) {
                this.updateFeed();
            }
        },
        updateFeed() {
            if (this.cancelToken !== undefined) {
                this.cancelToken.cancel("Operation canceled due to new request");
            }
            this.cancelToken = axios.CancelToken.source();
            this.isLoading = true;

            let apiRoute: string;
            const params = new URLSearchParams();
            params.append("page", this.page.toString());

            if (this.id) {
                params.append("id", this.id);
                apiRoute = "api_get_publications";
            } else {
                apiRoute = "api_get_threads";
            }

            axios
                .get<Paginate<Thread>>(Routing.generate(apiRoute), {
                    params,
                    cancelToken: this.cancelToken.token,
                })
                .then(({ data }) => {
                    if (data.pagination.has_next_page) {
                        this.page += 1;
                    }
                    this.totalPages = data.pagination.total_pages;
                    this.total = data.pagination.total;
                    this.threads = this.threads.concat(data.data);
                    this.isLoading = false;
                })
                .catch(err => {
                    if (axios.isCancel(err)) {
                        console.error(`Cancelling previous request: ${err.message}`);
                    }
                });
        },
    },
    computed: {
        isBottomReached() {
            return window.pageYOffset + window.innerHeight >= document.body.scrollHeight - 1000;
        },
    },
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
