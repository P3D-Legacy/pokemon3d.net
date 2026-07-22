export type User = {
    id: number;
    name: string;
    username: string;
    email: string;
    profile_photo_url?: string;
    email_verified_at?: string | null;
    unread_notifications_count?: number;
    is_admin?: boolean;
};

export type SharedPageProps = {
    auth: {
        user: User | null;
    };
    flash: {
        status?: string | null;
        error?: string | null;
        banner?: string | null;
        bannerStyle?: string | null;
        token?: string | null;
    };
    appName: string;
    locale: string;
    env: string;
    socialLogin: {
        discord: boolean;
        facebook: boolean;
        twitch: boolean;
        gamejolt: boolean;
        xenforo: boolean;
    };
};

export type HomeStat = {
    key: string;
    label: string;
    value: string;
    hint?: string | null;
};

export type PostCard = {
    uuid: string;
    slug: string;
    title: string;
    excerpt: string;
    sticky: boolean;
    published_at: string;
    published_for_humans: string;
    reading_time: string;
    comment_count: string;
    view_count: string;
    tag?: string | null;
    author: {
        username: string;
        profile_photo_url: string;
    };
    url: string;
};

export type Paginated<T> = {
    data: T[];
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
    current_page?: number;
    last_page?: number;
    per_page?: number;
    total?: number;
    meta?: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
};
