<template>
    <Story>
        <Variant title="Default">
            <time-elapsed></time-elapsed>
        </Variant>
        <Variant title="Secondes">
            <time-elapsed :date="dateSecondes"></time-elapsed>
        </Variant>
        <Variant title="Minutes">
            <time-elapsed :date="dateMinutes"></time-elapsed>
        </Variant>
        <Variant title="Hours">
            <time-elapsed :date="dateHours"></time-elapsed>
        </Variant>
        <Variant title="Days">
            <time-elapsed :date="dateDays"></time-elapsed>
        </Variant>
        <Variant title="Months">
            <time-elapsed :date="dateMonths"></time-elapsed>
        </Variant>
        <Variant title="Years">
            <time-elapsed :date="dateYears"></time-elapsed>
        </Variant>
    </Story>
</template>

<script setup lang="ts">
import { DateTime } from "luxon";
import TimeElapsed from "../vue/TimeElapsed/TimeElapsed.vue";

const dateSecondes = DateTime.now().minus({ seconds: 5 }).toISO();
const dateMinutes = DateTime.now().minus({ minutes: 5 }).toISO();
const dateHours = DateTime.now().minus({ hours: 5 }).toISO();
const dateDays = DateTime.now().minus({ days: 5 }).toISO();
const dateMonths = DateTime.now().minus({ months: 5 }).toISO();
const dateYears = DateTime.now().minus({ years: 5 }).toISO();
</script>
