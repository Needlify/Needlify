<template>
    <Story>
        <Variant title="Event">
            <div class="container">
                <timeline-thread :icon="ThreadIcon[event.type].icon" :icon-color="ThreadIcon[event.type].color">
                    <div class="event-content">
                        <span v-html="event.preview"></span>
                        <span class="dot">•</span>
                        <time-elapsed class="date" :date="event.publishedAt"></time-elapsed>
                    </div>
                </timeline-thread>
            </div>
        </Variant>
        <Variant title="Publication">
            <div class="container">
                <timeline-thread :icon="ThreadIcon[article.type].icon" :icon-color="ThreadIcon[article.type].color">
                    <div class="event-content in-publication">
                        <span>
                            Nouvelle publication dans le topic <a>{{ article.topic.name }}</a>
                        </span>
                    </div>

                    <div class="publication-content">
                        <h3>
                            <a>{{ article.title }}</a>
                        </h3>

                        <time-elapsed class="date" :date="article.publishedAt"></time-elapsed>

                        <div class="tag-container" v-show="article.tags.length > 0">
                            <tag :name="tag.name" :slug="tag.slug" v-for="(tag, indexTag) in article.tags" :key="indexTag"></tag>
                        </div>

                        <p class="description" :class="{ 'with-max-line': article.type === ThreadTypeVariationEnum.ARTICLE }">{{ article.preview }}</p>
                    </div>
                </timeline-thread>
            </div>
        </Variant>
    </Story>
</template>

<script setup lang="ts">
import TimelineThread from "../vue/Timeline/TimelineThread.vue";
import TimeElapsed from "../vue/TimeElapsed/TimeElapsed.vue";

import { ThreadIcon, ThreadTypeVariationEnum } from "../enum";

const event = {
    preview: "Le topic <strong>topic1</strong> a été créé",
    publishedAt: "2022-10-09T13:37:00+00:00",
    type: "event",
};

const article = {
    content:
        "Rerum ipsam culpa in veniam recusandae quam. Explicabo sint laudantium iure sint voluptatibus aspernatur. Et repellendus sed et at corrupti quod aut. Exercitationem sed laborum unde et quia aperiam architecto. Nesciunt a voluptatibus tempore qui esse in. Esse vero et eum atque unde vel. Eum autem velit voluptatem id eum quia. Voluptatibus laboriosam distinctio qui autem aut perferendis tempore. Eveniet dolore consequatur consequatur est maxime blanditiis. Tenetur beatae accusantium nesciunt recusandae quis. Voluptatem optio quasi tempora tempora. Ut nesciunt autem quos veritatis. Quia consequatur occaecati sit. Repellat ab numquam libero sequi voluptatem vitae est. Minima doloribus impedit necessitatibus expedita consectetur quo odit. Explicabo ut enim quae ipsa recusandae sapiente. Eum et non sed animi. At natus eos est nulla qui pariatur.",
    preview:
        "Temporibus ea repellendus ea aliquam aut officia. Id dolore voluptatem nam in ab magni. Est sequi ex amet. Nobis omnis quia ut aut debitis. Repellendus enim eveniet quos nobis odit dolores voluptate. Dolorum delectus maxime dicta illum accusantium eos. Ad distinctio sunt exercitationem. Quidem qui officiis rerum explicabo ad ipsa quis. Iusto et pariatur voluptatem eum consequatur ipsa et.",
    publishedAt: "2022-10-09T13:37:00+00:00",
    slug: "ea-accusantium-vel-cupiditate-tempore-eligendi-ullam-aut-placeat-ea-vitae-magnam-sit-eos-modi-b25c22cd",
    tags: [
        { name: "tag1", slug: "tag1" },
        { name: "tag3", slug: "tag3" },
        { name: "tag4", slug: "tag4" },
    ],
    title: "Ea accusantium vel cupiditate tempore. Eligendi ullam aut placeat. Ea vitae magnam sit eos modi.",
    topic: { name: "topic5", slug: "topic5" },
    type: "article",
};
</script>

<style lang="scss">
@import "../styles/mixins";

.container {
    margin-left: 50px;
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
