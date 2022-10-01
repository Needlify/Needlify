export type ThreadTypeVariation = "article" | "moodline" | "event";
export type ThreadIconAssociationType = Record<ThreadTypeVariation, { icon: string; color: string }>;

export type Classifier = {
    name: string;
    slug: string;
};

export type Tag = Classifier;

export type Topic = Classifier;

export type Event = {
    preview: string;
    publishedAt: string;
    type: ThreadTypeVariation;
};

export type Article = {
    content: string;
    preview: string;
    publishedAt: string;
    slug: string;
    tags: Array<Tag>;
    title: string;
    topic: Topic;
    type: ThreadTypeVariation;
};

export type Moodline = {
    preview: string;
    publishedAt: string;
    tags: Array<Tag>;
    topic: Topic;
    type: ThreadTypeVariation;
};

export type Thread = Event | Article | Moodline;

export type ThreadsQuery = {
    total: number;
    data: Array<Thread>;
};
