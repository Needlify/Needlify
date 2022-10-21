export type ThreadTypeVariation = "article" | "moodline" | "event";
export type ClassifierTypeVariation = "topic" | "tag";
export type ThreadIconAssociationType = Record<ThreadTypeVariation, { icon: string; color: string }>;
export type ClassifierTypeVariationType = Record<string, ClassifierTypeVariation>;

export type Classifier = {
    name: string;
    slug: string;
};

export type Tag = Classifier;

export type Topic = Classifier;

export type Thread = {
    preview: string;
    publishedAt: string;
    type: ThreadTypeVariation;
    content: string;
    slug: string;
    tags: Array<Tag>;
    title: string;
    topic: Topic;
};

export type ThreadsQuery = {
    total: number;
    data: Array<Thread>;
};

export type AlertType = "success" | "warning" | "error";
