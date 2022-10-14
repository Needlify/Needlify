<template>
    <story>
        <variant title="Event">
            <div class="container">
                <thread-event :type="event.type" :preview="event.preview" :published-at="event.publishedAt" :display-line="true" />
            </div>
        </variant>
        <variant title="Article">
            <div class="container">
                <thread-publication
                    :type="article.type"
                    :slug="article.slug"
                    :preview="article.preview"
                    :title="article.title"
                    :published-at="article.publishedAt"
                    :tags="article.tags"
                    :topic="article.topic"
                    :display-line="true"
                />
            </div>
        </variant>
        <variant title="Moodline">
            <div class="container">
                <thread-publication
                    :type="moodline.type"
                    :preview="moodline.preview"
                    :published-at="moodline.publishedAt"
                    :tags="moodline.tags"
                    :topic="moodline.topic"
                    :display-line="true"
                />
            </div>
        </variant>
    </story>
</template>

<script setup lang="ts">
import Routing from "fos-router";
import { ThreadTypeVariation } from "../types";
import ThreadEvent from "../vue/Thread/ThreadEvent.vue";
import ThreadPublication from "../vue/Thread/ThreadPublication.vue";

import routes from "../js/routes.json";

Routing.setRoutingData(routes);

const event = {
    preview: "Le topic <strong>topic1</strong> a été créé",
    publishedAt: "2022-10-09T13:37:00+00:00",
    type: "event" as ThreadTypeVariation,
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
    type: "article" as ThreadTypeVariation,
};

const moodline = {
    preview:
        "Enim tempora in atque quod enim modi ex autem. Numquam dolores nulla sit earum quasi rerum est. Non perferendis explicabo aut. Sed id nesciunt id asperiores aut. Veniam enim quis nemo iste nulla eos necessitatibus. Aut non quia molestiae assumenda nulla quia eos.",
    publishedAt: "2022-10-09T13:37:00+00:00",
    tags: [
        { name: "tag1", slug: "tag1" },
        { name: "tag3", slug: "tag3" },
        { name: "tag5", slug: "tag5" },
    ],
    topic: { name: "topic5", slug: "topic5" },
    type: "moodline" as ThreadTypeVariation,
};
</script>

<style scoped lang="scss">
.container {
    margin-left: 50px;
}
</style>
