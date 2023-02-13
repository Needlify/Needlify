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

export type Pagination = {
    total: number;
    count: number;
    offset: number;
    items_per_page: number;
    total_pages: number;
    current_page: number;
    has_next_page: boolean;
    has_previous_page: boolean;
};

export type Paginate<T> = {
    data: Array<T>;
    pagination: Pagination;
};

export type AlertType = "success" | "warning" | "error";

// https://github.com/Ionaru/easy-markdown-editor/blob/abead2a06809a9003ab8500cb3d1f1bb19fe16ea/types/easymde.d.ts#L30-L55
export type ToolbarButton =
    | "bold"
    | "italic"
    | "quote"
    | "unordered-list"
    | "ordered-list"
    | "link"
    | "image"
    | "upload-image"
    | "strikethrough"
    | "code"
    | "table"
    | "redo"
    | "heading"
    | "undo"
    | "heading-bigger"
    | "heading-smaller"
    | "heading-1"
    | "heading-2"
    | "heading-3"
    | "clean-block"
    | "horizontal-rule"
    | "preview"
    | "side-by-side"
    | "fullscreen"
    | "guide";
