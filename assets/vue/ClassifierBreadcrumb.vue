<template>
    <section id="container">
        <div class="first-two-icon" v-if="selector === ClassifierTypeVariationEnum.TOPIC">
            <feather-icon icon="tag" size="18px" stroke-width="2px"></feather-icon>
        </div>
        <div class="first-two-icon" v-else>
            <feather-icon icon="hash" size="18px" stroke-width="2px"></feather-icon>
        </div>

        <div class="first-two-icon">
            <feather-icon icon="chevron-right" size="12px" stroke-width="3px"></feather-icon>
        </div>

        <span class="secondary" v-if="selector === ClassifierTypeVariationEnum.TOPIC">Topic</span>
        <span class="secondary" v-else>Tag</span>

        <feather-icon icon="chevron-right" size="12px" stroke-width="3px"></feather-icon>

        <span class="primary" :title="classifierName">{{ classifierName }}</span>
    </section>
</template>

<script lang="ts" setup>
import { ClassifierTypeVariationEnum } from "../enum";
import { ClassifierTypeVariation } from "../types";
import FeatherIcon from "./FeatherIcon.vue";

defineProps<{
    classifierName: string;
    selector: ClassifierTypeVariation;
}>();
</script>

<style lang="scss" scoped>
@import "../styles/mixins";

#container {
    display: flex;
    align-items: center;
    color: var(--light-dark);
    column-gap: 6px;
    font-size: 18px;

    .secondary {
        color: var(--light-dark);
    }

    .primary {
        color: var(--dark-light);
        font-weight: 700;
        flex-grow: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 0; /* Pour éviter qu'en format mobile, la margin de la timeline s'enlève */
    }

    @include maxWidth(600px) {
        font-size: 16px;

        .first-two-icon {
            display: none;
        }
    }
}
</style>
