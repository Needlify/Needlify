<template>
    <div class="custom-input">
        <feather-icon :icon="props.icon" class="input-icon prefix-icon" />
        <input :type="currentType" :name="props.name" :value="props.value" :placeholder="props.placeholder" required />

        <div v-show="props.type === 'password'" @click="toggleRevealPassword">
            <feather-icon v-show="reveal" icon="eye" class="input-icon password-reveal-icon" />
            <feather-icon v-show="!reveal" icon="eye-off" class="input-icon password-reveal-icon" />
        </div>
    </div>
    <div class="input-help-message">{{ props.help }}</div>
</template>

<script setup lang="ts">
import { onMounted, ref } from "vue";
import FeatherIcon from "./FeatherIcon.vue";

const props = defineProps<{
    type: "text" | "email" | "password";
    value: string;
    placeholder: string;
    icon: string;
    name: string;
    help: string;
}>();

const reveal = ref(false);
const currentType = ref("");

onMounted(() => {
    currentType.value = props.type;
});

const toggleRevealPassword = () => {
    reveal.value = !reveal.value;
    currentType.value = reveal.value ? "text" : "password";
};
</script>

<style lang="scss" scoped>
.custom-input {
    position: relative;
    display: inline-block;

    .prefix-icon {
        top: 50%;
        left: 22px;
    }

    .input-icon {
        width: 20px;
        height: 20px;
        stroke-width: 2.5px;
        stroke: var(--primary);
        position: absolute;
        transform: translate(-50%, -50%);
    }

    .password-reveal-icon {
        top: 50%;
        right: 8px;
    }

    input {
        padding: 12px 10px 10px 42px;
        width: 100%;
        border: 2px solid var(--light-medium);
        border-radius: 8px;
        color: var(--dark-light);
        transition: border 0.2s ease-in-out;
        font-size: 14px;

        &[type="password"] {
            padding-right: 42px;
        }

        &::placeholder {
            color: var(--light-dark);
            user-select: none;
        }

        &:focus {
            border: 2px solid var(--primary);
        }
    }
}

.input-help-message {
    color: var(--light-dark);
    font-size: 12px;
    margin-top: 4px;
}
</style>
