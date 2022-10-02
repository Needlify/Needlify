<template>
    <div :title="publishedAtWithTimezone">{{ getDateDiff }}</div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { DateTime, Interval } from "luxon";

const props = defineProps({
    date: {
        type: String,
        default: DateTime.now().toISO(),
    },
});

const getDateDiff = computed<string | null>(() => {
    const publishedAt = DateTime.fromISO(props.date.toString()).setZone("utc");
    const now = DateTime.utc();

    return DateTime.now()
        .toLocal()
        .minus({ seconds: Interval.fromDateTimes(publishedAt, now).length("seconds") })
        .toRelative();
});

const publishedAtWithTimezone = computed<string>(() => DateTime.fromISO(props.date.toString()).toLocal().toLocaleString(DateTime.DATETIME_MED));
</script>

<style lang="scss" scoped>
div {
    display: inline-block;
}
</style>
