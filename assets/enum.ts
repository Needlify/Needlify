import { ThreadIconAssociationType } from "./types.d";

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
} as ThreadIconAssociationType;

export const ThreadTypeVariationEnum = {
    ARTICLE: "article",
    MOODLINE: "moodline",
    EVENT: "event",
    PUBLICATION: ["article", "moodline"],
};
