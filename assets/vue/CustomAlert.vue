<template>
    <Transition>
        <div class="alert" v-show="show">
            <div class="alert-container">
                <div class="alert-icon-container">
                    <feather-icon :icon="iconComposition.icon" :color="iconComposition.color" size="20px" />
                </div>
                <div class="alert-content-container">
                    <p class="alert-message">{{ props.message }}</p>
                    <div class="alert-description" v-show="description">{{ props.description }}</div>
                </div>
                <div class="alert-close-container" v-show="props.dismissible" @click="hide">
                    <feather-icon class="alert-close" icon="x" size="20px" stroke-width="1.5px" color="#6d777e" />
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup lang="ts">
import { computed, ref } from "vue";
import FeatherIcon from "./FeatherIcon.vue";
import type { AlertType } from "../types.d";

const show = ref(true);

const hide = () => {
    show.value = false;
};

const props = withDefaults(
    defineProps<{
        message: string;
        description: string;
        type: AlertType;
        dismissible: boolean;
    }>(),
    {
        dismissible: true,
    },
);

const iconComposition = computed(() => {
    switch (props.type) {
        case "success":
            return {
                icon: "check-circle",
                color: "#15925e", // --success
            };

        case "warning":
            return {
                icon: "alert-circle",
                color: "#ff6f3c", // --warning
            };

        case "error":
        default:
            return {
                icon: "x-circle",
                color: "#ea5455", // --warning
            };
    }
});
</script>

<style lang="scss">
@import "../styles/mixins";

.v-enter-active,
.v-leave-active {
    transition: opacity 200ms ease-in-out;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}

.alert {
    border-radius: 6px;
    padding: 18px 14px;
    box-shadow: 0 2px 6px var(--dark-transparent);

    @include maxWidth(600px) {
        padding: 12px 10px;
    }

    .alert-container {
        display: flex;
        column-gap: 14px;

        @include maxWidth(600px) {
            column-gap: 8px;
        }

        .alert-content-container {
            flex-grow: 1;

            .alert-message {
                margin: 0;
                font-weight: 500;
                font-size: 16px;
                margin-top: 1px;
                @include maxWidth(600px) {
                    font-size: 13px;
                }
            }

            .alert-description {
                font-size: 15px;
                margin-top: 8px;
                color: var(--dark-soft);
                line-height: 18px;
                @include maxWidth(600px) {
                    font-size: 12px;
                }
            }
        }

        .alert-close-container {
            .alert-close {
                cursor: pointer;
            }
        }
    }
}
</style>
