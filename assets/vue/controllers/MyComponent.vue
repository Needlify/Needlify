<template>
    <div>
        Hello {{ name }}!
        <x-spinner></x-spinner>
    </div>
</template>

<script setup lang="ts">
import "../../components/Spinner";

defineProps({
    name: String,
});
</script>
