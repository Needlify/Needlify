<template>
    <a class="tag" :href="generateTagUrl(props.slug)">
        <FeatherIcon icon="hash" color="#a8a8a8" stroke-width="2.5px" size="14px"></FeatherIcon>
        <span>{{ props.name }}</span>
    </a>
</template>

<script setup lang="ts">
import Routing from "fos-router";
import FeatherIcon from "../FeatherIcon/FeatherIcon.vue";

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    slug: {
        type: String,
        required: true,
    },
});

// TODO Changer l'url des tags
const generateTagUrl = (slug: string) => Routing.generate("app_tag", { slug });
</script>

<style lang="scss" scoped>
@import "../../styles/mixins";

.tag {
    font-family: Supreme;
    display: inline-flex;
    align-items: center;
    color: var(--dark-soft);
    column-gap: 4px;
    border: 2px solid var(--light-soft);
    border-radius: 6px;
    padding: 4px 6px;
    text-decoration: none;
    transition: background-color 200ms ease-in-out;

    @include maxWidth(600px) {
        column-gap: 1px;
        padding: 3px 4px;
    }

    &:hover {
        background-color: var(--light);
    }
}

span {
    padding: 0;
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    line-height: 0;

    @include maxWidth(600px) {
        font-size: 12px;
    }
}
</style>
