<template>
    <story>
        <variant title="Default">
            <time-elapsed></time-elapsed>
        </variant>
        <variant title="Secondes">
            <time-elapsed :date="dateSecondes"></time-elapsed>
        </variant>
        <variant title="Minutes">
            <time-elapsed :date="dateMinutes"></time-elapsed>
        </variant>
        <variant title="Hours">
            <time-elapsed :date="dateHours"></time-elapsed>
        </variant>
        <variant title="Days">
            <time-elapsed :date="dateDays"></time-elapsed>
        </variant>
        <variant title="Months">
            <time-elapsed :date="dateMonths"></time-elapsed>
        </variant>
        <variant title="Years">
            <time-elapsed :date="dateYears"></time-elapsed>
        </variant>
    </story>
</template>

<script setup lang="ts">
import { DateTime } from "luxon";
import TimeElapsed from "../vue/TimeElapsed.vue";

const dateSecondes = DateTime.now().minus({ seconds: 5 }).toISO();
const dateMinutes = DateTime.now().minus({ minutes: 5 }).toISO();
const dateHours = DateTime.now().minus({ hours: 5 }).toISO();
const dateDays = DateTime.now().minus({ days: 5 }).toISO();
const dateMonths = DateTime.now().minus({ months: 5 }).toISO();
const dateYears = DateTime.now().minus({ years: 5 }).toISO();
</script>
