<template>
    <div class="alert" :class="`alert-${type}`">
        <div class="alert-indicator"></div>
        <div class="alert-container">
            <h6 class="alert-title" v-show="title">
                {{ title }}
            </h6>
            <div class="alert-message">{{ message }}</div>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    message: string;
    title: string;
    type: "success" | "warning" | "error";
}>();
</script>

<style lang="scss">
.alert {
    border-radius: 6px;
    overflow: hidden;
    position: relative;

    .alert-indicator {
        position: absolute;
        width: 6px;
        height: 100%;
    }

    .alert-container {
        padding: 11px 11px 11px 22px;
    }

    .alert-container {
        position: relative;

        .alert-message {
            font-size: 14px;
            color: var(--text-danger);
        }

        .alert-title {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text);
        }
    }

    &.alert-error {
        .alert-indicator {
            background-color: var(--danger);
        }
        .alert-container {
            background-color: var(--danger-transparent);

            .alert-message {
                color: var(--text-danger);
            }
        }
    }

    &.alert-warning {
        .alert-indicator {
            background-color: var(--warning);
        }
        .alert-container {
            background-color: var(--warning-transparent);

            .alert-message {
                color: var(--text-warning);
            }
        }
    }

    &.alert-success {
        .alert-indicator {
            background-color: var(--success);
        }
        .alert-container {
            background-color: var(--success-transparent);

            .alert-message {
                color: var(--text-success);
            }
        }
    }

    &.alert-info {
        .alert-indicator {
            background-color: var(--primary);
        }
        .alert-container {
            background-color: var(--primary-transparent);

            .alert-message {
                color: var(--text-primary);
            }
        }
    }
}
</style>
