import { ClassifierTypeVariationType, ThreadIconAssociationType } from "./types.d";

/* eslint-disable import/prefer-default-export */
export const ThreadIcon = {
    article: {
        icon: "edit-3",
        color: "#5c81fc",
    },
    moodline: {
        icon: "zap",
        color: "#54BAB9",
    },
    event: {
        icon: "bell",
        color: "#979dac",
        // color: "#7d8597",
    },
    course: {
        icon: "award",
        color: "#9c65c5",
    },
} as ThreadIconAssociationType;

export const ThreadTypeVariationEnum = {
    ARTICLE: "article",
    MOODLINE: "moodline",
    EVENT: "event",
    COURSE: "course",
    PUBLICATION: ["article", "moodline", "course"],
    DOCUMENT: ["article", "course"],
};

export const CourseDifficultyEnum = {
    EASY: "easy",
    MEDIUM: "medium",
    HARD: "hard",
    EXPERT: "expert",
};

export const ClassifierTypeVariationEnum = {
    TOPIC: "topic",
    TAG: "tag",
} as ClassifierTypeVariationType;

export const ClassifierTypeVariationList = Object.values(ClassifierTypeVariationEnum);
